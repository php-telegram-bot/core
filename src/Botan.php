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

use Longman\TelegramBot\Exception\TelegramException;

/**
 * Class Botan
 *
 * Integration with http://botan.io statistics service for Telegram bots
 */
class Botan
{
    /**
     * @var string Tracker request url
     */
    protected static $track_url = 'https://api.botan.io/track?token=#TOKEN&uid=#UID&name=#NAME';

    /**
     * @var string Url Shortener request url
     */
    protected static $shortener_url = 'https://api.botan.io/s/?token=#TOKEN&user_ids=#UID&url=#URL';

    /**
     * @var string Yandex AppMetrica application key
     */
    protected static $token = '';

    /**
     * @var string The actual command that is going to be reported
     */
    public static $command = '';

    /**
     * Initilize botan
     */
    public static function initializeBotan($token)
    {
        if (empty($token) || !is_string($token)) {
            throw new TelegramException('Botan token should be a string!');
        }
        self::$token = $token;
        BotanDB::initializeBotanDb();
    }

    /**
     * Lock function to make sure only the first command is reported
     * ( in case commands are calling other commands $telegram->executedCommand() )
     *
     * @param  string $command
     */
    public static function lock($command = '')
    {
        if (empty(self::$command)) {
            self::$command = $command;
        }
    }

    /**
     * Track function
     *
     * @param  string $input
     * @param  string $command
     *
     * @return bool|string
     * @throws TelegramException
     */
    public static function track($input, $command = '')
    {
        if (empty(self::$token) || $command != self::$command) {
            return false;
        }

        if (empty($input)) {
            throw new TelegramException('Input is empty!');
        }

        $obj = json_decode($input, true);
        if (isset($obj['message'])) {
            $data = $obj['message'];
            $event_name = 'Message';

            if (isset($obj['message']['entities']) && is_array($obj['message']['entities'])) {
                foreach ($obj['message']['entities'] as $entity) {
                    if ($entity['type'] == 'bot_command' && $entity['offset'] == 0) {
                        if (strtolower($command) == 'generic') {
                            $command = 'Generic';
                        } else {
                            $command = '/' . strtolower($command);
                        }

                        $event_name = 'Command (' . $command . ')';
                        break;
                    }
                }
            }
        } elseif (isset($obj['inline_query'])) {
            $data = $obj['inline_query'];
            $event_name = 'Inline Query';
        } elseif (isset($obj['chosen_inline_result'])) {
            $data = $obj['chosen_inline_result'];
            $event_name = 'Chosen Inline Result';
        } elseif (isset($obj['callback_query'])) {
            $data = $obj['callback_query'];
            $event_name = 'Callback Query';
        }

        if (empty($event_name)) {
            return false;
        }

        self::$command = '';

        $uid = $data['from']['id'];
        $request = str_replace(
            ['#TOKEN', '#UID', '#NAME'],
            [self::$token, $uid, urlencode($event_name)],
            self::$track_url
        );

        $options = [
            'http' => [
                'header'  => 'Content-Type: application/json',
                'method'  => 'POST',
                'content' => json_encode($data),
                'ignore_errors' => true
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($request, false, $context);
        $responseData = json_decode($response, true);

        if ($responseData['status'] != 'accepted') {
            error_log('Botan.io API replied with error: ' . $response);
        }

        return $responseData;
    }

    /**
     * Url Shortener function
     *
     * @param  $url
     * @param  $user_id
     *
     * @return string
     * @throws TelegramException
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

        if (!empty($cached[0]['short_url'])) {
            return $cached[0]['short_url'];
        }

        $request = str_replace(
            ['#TOKEN', '#UID', '#URL'],
            [self::$token, $user_id, urlencode($url)],
            self::$shortener_url
        );

        $options = [
            'http' => [
                'ignore_errors' => true,
                'timeout' => 3
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($request, false, $context);

        if (!filter_var($response, FILTER_VALIDATE_URL) === false) {
            BotanDB::insertShortUrl($user_id, $url, $response);
        } else {
            error_log('Botan.io API replied with error: ' . $response);
            return $url;
        }

        return $response;
    }
}
