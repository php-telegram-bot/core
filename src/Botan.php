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
     *  - override which command is tracked when commands call other commands with executeCommand()
     *
     * @var string
     */
    public static $command = '';

    /**
     * Initialize Botan
     *
     * @param  string $token
     * @param  array  $options
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function initializeBotan($token, array $options = [])
    {
        if (empty($token)) {
            throw new TelegramException('Botan token is empty!');
        }

        $options_default = [
            'timeout' => 3
        ];

        $options = array_merge($options_default, $options);

        if (!is_numeric($options['timeout'])) {
            throw new TelegramException('Timeout must be a number!');
        }

        self::$token  = $token;
        self::$client = new Client(['base_uri' => self::$api_base_uri, 'timeout' => $options['timeout']]);

        BotanDB::initializeBotanDb();
    }

    /**
     * Lock function to make sure only the first command is reported (the one user requested)
     *
     *  This is in case commands are calling other commands with executeCommand()
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
        $command = strtolower($command);

        if (empty(self::$token) || $command !== self::$command) {
            return false;
        }

        if (empty($update)) {
            throw new TelegramException('Update object is empty!');
        }

        // Release the lock in case this is getUpdates instance in foreach loop
        self::$command = '';

        $data = [];
        $update_data = (array) $update; // For now, this is the only way
        $update_type = $update->getUpdateType();

        $update_object_names = [
            'message' => 'Message',
            'edited_message' => 'Edited Message',
            'channel_post' => 'Channel Post',
            'edited_channel_post' => 'Edited Channel Post',
            'inline_query' => 'Inline Query',
            'chosen_inline_result' => 'Chosen Inline Result',
            'callback_query' => 'Callback Query'
        ];

        if (array_key_exists($update_type, $update_object_names)) {
            $data       = $update_data[$update_type];
            $event_name = $update_object_names[$update_type];

            if ($update_type === 'message') {
                if ($update->getMessage()->getEntities()) {
                    foreach ($update->getMessage()->getEntities() as $entity) {
                        if ($entity->getType() === 'bot_command' && $entity->getOffset() === 0) {
                            if ($command === 'generic') {
                                $command = 'Generic';
                            } elseif ($command === 'genericmessage') {  // This should not happen as it equals normal message but leaving it as a fail-safe
                                $command = 'Generic Message';
                            } else {
                                $command = '/' . $command;
                            }

                            $event_name = 'Command (' . $command . ')';
                            break;
                        }
                    }
                }
            }
        }

        if (empty($event_name)) {
            TelegramLog::error("Botan.io stats report failed, no suitable update object found!");
            return false;
        }

        // In case there is no from field assign id = 0
        $uid = isset($data['from']['id']) ? $data['from']['id'] : 0;

        $result = null;

        try {
            $response = self::$client->post(
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

            $result = (string) $response->getBody();
        } catch (RequestException $e) {
            $result = $e->getMessage();
        } finally {
            $responseData = json_decode($result, true);

            if ($responseData['status'] !== 'accepted') {
                TelegramLog::debug('Botan.io stats report failed: ' . ($result ?: 'empty response') . "\n\n");

                return false;
            }

            return $responseData;
        }
    }

    /**
     * Url Shortener function
     *
     * @param  string  $url
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

        if ($cached = BotanDB::selectShortUrl($url, $user_id)) {
            return $cached;
        }

        try {
            $response = self::$client->post(
                str_replace(
                    ['#TOKEN', '#UID', '#URL'],
                    [self::$token, $user_id, urlencode($url)],
                    '/s/?token=#TOKEN&user_ids=#UID&url=#URL'
                )
            );

            $result = (string) $response->getBody();
        } catch (RequestException $e) {
            $result = $e->getMessage();
        } finally {
            if (filter_var($result, FILTER_VALIDATE_URL) !== false) {
                BotanDB::insertShortUrl($url, $user_id, $result);
                return $result;
            } else {
                TelegramLog::debug('Botan.io URL shortening failed for \'' . $url . '\': ' . ($result ?: 'empty response') . "\n\n");

                return $url;
            }
        }
    }
}
