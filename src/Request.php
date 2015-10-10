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
        'sendVoice',
        'sendLocation',
        'sendChatAction',
        'getUserProfilePhotos',
        'getUpdates',
        'setWebhook',
    );

    public static function initialize(Telegram $telegram)
    {
        if (is_object($telegram)) {
            self::$telegram = $telegram;
        } else {
            throw new TelegramException('Telegram pointer is empty!');
        }
    }

    public static function setInputRaw($input)
    {
        if (is_string($input) | $input == false) {
            self::$input = $input;
        } else {
            throw new TelegramException("Log input is not a string");
        }
    }

    public static function getInput()
    {
        if ($update = self::$telegram->getCustomUpdate()) {
            self::setInputRaw($update);
        } else {
            self::setInputRaw(file_get_contents('php://input'));
        }
        self::log(self::$input);
        return self::$input;
    }


    private static function log($string)
    {
        if (!self::$telegram->getLogRequests()) {
            return false;
        }
        $path = self::$telegram->getLogPath();
        if (!$path) {
            return false;
        }

        //$status = file_put_contents($path, self::$input . "\n", FILE_APPEND);
        $status = file_put_contents($path, $string . "\n", FILE_APPEND);
        return $status;
    }

    public static function generateGeneralFakeServerSesponse($data = null)
    {
        //PARAM BINDED IN PHPUNIT TEST FOR TestServerResponse.php
        //Maybe this is not the best possible implementation

        //No value set in $data ie testing setWekhook
        //Provided $data['chat_id'] ie testing sendMessage

        $fake_response['ok'] = true; // :)

        if (!isset($data)) {
            $fake_response['result'] = true;
        }

        //some data to let iniatilize the class method SendMessage
        if (isset($data['chat_id'])) {
            $data['message_id'] = '1234';
            $data['date'] = '1441378360';
            $data['from'] = array( 'id' => 123456789 ,'first_name' => 'botname', 'username'=> 'namebot');
            $data['chat'] = array('id'=> $data['chat_id'] );

            $fake_response['result'] = $data;
        }

        return $fake_response;
    }

    public static function executeCurl($action, array $data)
    {

        $ch = curl_init();
        if ($ch === false) {
            throw new TelegramException('Curl failed to initialize');
        }

        $curlConfig = array(
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
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

        if (self::$telegram->getLogVerbosity() >= 3) {
            $curlConfig[CURLOPT_VERBOSE] = true;
            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);
            //Not so useful
            //$info = curl_getinfo($ch);
            //echo "Info\n";
            //print_r($info);
        }

        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);

        //Logging curl requests
        if (self::$telegram->getLogVerbosity() >= 3) {
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);
            self::log("Verbose curl output:\n". htmlspecialchars($verboseLog). "\n");
        }

        //Logging getUpdates Update
        //Logging curl updates
        if ($action == 'getUpdates' & self::$telegram->getLogVerbosity() >=1 | self::$telegram->getLogVerbosity() >=3) {
            self::setInputRaw($result);
            self::log($result);
        }

        if ($result === false) {
            throw new TelegramException(curl_error($ch), curl_errno($ch));
        }
        if (empty($result)) {
            throw new TelegramException('Empty server response');
        }

        curl_close($ch);
        return $result;
    }

    public static function send($action, array $data = null)
    {
        if (!in_array($action, self::$methods)) {
            throw new TelegramException('This methods doesn\'t exist!');
        }

        if (defined('PHPUNIT_TESTSUITE')) {
            $fake_response = self::generateGeneralFakeServerSesponse($data);
            return new ServerResponse($fake_response, self::$telegram->getBotName());
        }

        $result = self::executeCurl($action, $data);

        $bot_name = self::$telegram->getBotName();
        return new ServerResponse(json_decode($result, true), $bot_name);
    }

    public static function getMe()
    {
        $result = self::send('getMe');
        return $result;
    }

    public static function sendMessage(array $data)
    {

        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        $result = self::send('sendMessage', $data);
        return $result;
    }

    //TODO forwardMessage
    //sendPhoto
    //sendAudio
    //sendDocument
    //sendSticker
    //sendVideo
    //sendVoice
    public static function sendLocation(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        $result = self::send('sendLocation', $data);
        return $result;
    }

    public static function sendChatAction(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        $result = self::send('sendChatAction', $data);
        return $result;
    }

    //getUserProfilePhotos

    public static function getUpdates($data)
    {
        $result = self::send('getUpdates', $data);
        return $result;
    }

    public static function setWebhook($url)
    {
        $result = self::send('setWebhook', array('url' => $url));
        return $result;
    }

    //getFile
}
