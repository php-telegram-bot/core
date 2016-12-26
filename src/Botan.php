<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * Class Botan
 *
 * Integration with http://botan.io statistics service for Telegram bots
 */
class Botan
{
    /**
     * Botan.io API URL
     *
     * @var string
     */
    protected static $api_base_uri = 'https://api.botan.io';

    /**
     * Yandex AppMetrica application key
     *
     * @var string
     */
    protected static $token = '';

    /**
     * Guzzle Client object
     *
     * @var \GuzzleHttp\Client
     */
    private static $client;

    /**
     * The actual command that is going to be reported
     *
     *  Set as public to let the developers either:
     *  - block tracking from inside commands by setting the value to non-existent command
     *  - override which command is tracked when commands call other commands with executedCommand()
     *
     * @var string
     */
    public static $command = '';

    /**
     * Initialize Botan
     *
     * @param  string $token
     * @param  integer $timeout
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function initializeBotan($token, $timeout = 3)
    {
        if (empty($token)) {
            throw new TelegramException('Botan token is empty!');
        }

        if (!is_numeric($timeout)) {
            throw new TelegramException('Timeout must be a number!');
        }

        self::$token   = $token;
        self::$client  = new Client(['base_uri' => self::$api_base_uri, 'timeout' => $timeout]);

        BotanDB::initializeBotanDb();
    }

    /**
     * Lock function to make sure only the first command is reported (the one user requested)
     *
     *  This is in case commands are calling other commands with executedCommand()
     *
     * @param string $command
     */
    public static function lock($command = '')
    {
        if (empty(self::$command)) {
            self::$command = strtolower($command);
        }
    }

    /**
     * Track function
     *
     * @param  \Longman\TelegramBot\Entities\Update $update
     * @param  string $command
     *
     * @return bool|string
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function track(Update $update, $command = '')
    {
        if (empty(self::$token) || strtolower($command) !== self::$command) {
            return false;
        }

        if (empty($update)) {
            throw new TelegramException('Update object is empty!');
        }

        // Release the lock in case someone runs getUpdates in foreach loop
        self::$command = '';

        // For now, this is the only way
        $update_data = (array) $update;

        $data = [];
        if ($update->getMessage()) {
            $data       = $update_data['message'];
            $event_name = 'Generic Message';

            if (!empty($data['entities']) && is_array($data['entities'])) {
                foreach ($data['entities'] as $entity) {
                    if ($entity['type'] === 'bot_command' && $entity['offset'] === 0) {
                        if (strtolower($command) === 'generic') {
                            $command = 'Generic';
                        } elseif (strtolower($command) === 'genericmessage') {
                            $command = 'Generic Message';
                        } else {
                            $command = '/' . strtolower($command);
                        }

                        $event_name = 'Command (' . $command . ')';
                        break;
                    }
                }
            }
        } elseif ($update->getEditedMessage()) {
            $data       = $update_data['edited_message'];
            $event_name = 'Edited Message';
        } elseif ($update->getChannelPost()) {
            $data       = $update_data['channel_post'];
            $event_name = 'Channel Post';
        } elseif ($update->getEditedChannelPost()) {
            $data       = $update_data['edited_channel_post'];
            $event_name = 'Edited Channel Post';
        } elseif ($update->getInlineQuery()) {
            $data       = $update_data['inline_query'];
            $event_name = 'Inline Query';
        } elseif ($update->getChosenInlineResult()) {
            $data       = $update_data['chosen_inline_result'];
            $event_name = 'Chosen Inline Result';
        } elseif ($update->getCallbackQuery()) {
            $data       = $update_data['callback_query'];
            $event_name = 'Callback Query';
        }

        if (empty($event_name)) {
            return false;
        }

        // In case there is no from field (channel posts) assign chat id
        if (isset($data['from']['id'])) {
            $uid = $data['from']['id'];
        } elseif (isset($data['chat']['id'])) {
            $uid = $data['chat']['id'];
        } else {
            $uid = 0;   // if that fails too assign id = 0
        }

        try {
            $response = self::$client->request(
                'POST',
                str_replace(
                    ['#TOKEN', '#UID', '#NAME'],
                    [self::$token, $uid, urlencode($event_name)],
                    '/track?token=#TOKEN&uid=#UID&name=#NAME'
                ),
                [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $data
                ]
            );

            $response = (string) $response->getBody();
        } catch (RequestException $e) {
            $response = ($e->getResponse()) ? (string) $e->getResponse()->getBody() : '';
        } finally {
            $responseData = json_decode($response, true);

            if ($responseData['status'] !== 'accepted') {
                TelegramLog::debug("Botan.io API replied with error:\n$response\n\n");
                return false;
            }

            return $responseData;
        }
    }

    /**
     * Url Shortener function
     *
     * @param  string $url
     * @param  integer $user_id
     *
     * @return string
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function shortenUrl($url, $user_id)
    {
        if (empty(self::$token)) {
            return $url;
        }

        if (empty($user_id)) {
            throw new TelegramException('User id is empty!');
        }

        $cached = BotanDB::selectShortUrl($user_id, $url);

        if (!empty($cached)) {
            return $cached;
        }

        try {
            $response = self::$client->request(
                'POST',
                str_replace(
                    ['#TOKEN', '#UID', '#URL'],
                    [self::$token, $user_id, urlencode($url)],
                    '/s/?token=#TOKEN&user_ids=#UID&url=#URL'
                )
            );

            $response = (string) $response->getBody();
        } catch (RequestException $e) {
            $response = ($e->getResponse()) ? (string) $e->getResponse()->getBody() : '';
        } finally {
            if (!filter_var($response, FILTER_VALIDATE_URL) === false) {
                BotanDB::insertShortUrl($user_id, $url, $response);
                return $response;
            } else {
                TelegramLog::error("Botan.io URL shortening failed - API replied with error:\n$response\n\n");
                return $url;
            }
        }
    }
}
