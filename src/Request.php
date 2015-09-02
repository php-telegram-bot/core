<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\User;

class Request
{
    private static $telegram;
    private static $input;

    private static $methods = array(
        'getMe',
        'sendMessage',
        'forwardMessage',
        'sendPhoto',
        'sendAudio',
        'sendDocument',
        'sendSticker',
        'sendVideo',
        'sendLocation',
        'sendChatAction',
        'getUserProfilePhotos',
        'getUpdates',
        'setWebhook',
    );

    public static function initialize(Telegram $telegram)
    {
        self::$telegram = $telegram;
    }

    public static function getInput()
    {
        if ($update = self::$telegram->getCustomUpdate()) {
            self::$input = $update;
        } else {
            self::$input = file_get_contents('php://input');
        }
        self::log();
        return self::$input;
    }

    private static function log()
    {
        if (!self::$telegram->getLogRequests()) {
            return false;
        }
        $path = self::$telegram->getLogPath();
        if (!$path) {
            return false;
        }

        $status = file_put_contents($path, self::$input . "\n", FILE_APPEND);

        return $status;
    }

    public static function send($action, array $data = null)
    {

        if (!in_array($action, self::$methods)) {
            throw new TelegramException('This methods doesn\'t exixt!');
        }

        if (defined('PHPUNIT_TESTSUITE')) {
            $fake_response['ok'] = 1;

            //some fake data just to let iniatilize the class
            $data['message_id'] = '123';
            $data['date'] = '123';

            $data['from'] = array( 'id' => 123,'first_name' => 'botname', 'username'=> 'namebot');

            $data['chat'] = array('id'=> $data['chat_id'] );
            $fake_response['result'] = $data;

            return new ServerResponse($fake_response, self::$telegram->getBotName());
        }

        $ch = curl_init();
        $curlConfig = array(
            CURLOPT_URL => 'https://api.telegram.org/bot' . self::$telegram->getApiKey() . '/' . $action,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true
        );

        if (!empty($data)) {
            if (!empty($data['text']) && substr($data['text'], 0, 1) === '@') {
                $data['text'] = ' ' . $data['text'];
            }
            $curlConfig[CURLOPT_POSTFIELDS] = $data;
        }

        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);

        if (empty($result)) {
            $response['ok'] = 1;
            $response['error_code'] = 1;
            $response['description'] = 'Empty server response';
        }

        return new ServerResponse(json_decode($result, true), self::$telegram->getBotName());
    }

    public static function sendMessage(array $data)
    {

        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        $result = self::send('sendMessage', $data);
        return $result;
    }

    public static function getMe()
    {

        $result = self::send('getMe');
        return $result;
    }

    public static function setWebhook($url)
    {
        $result = self::send('setWebhook', array('url' => $url));
        return $result;
    }
}
