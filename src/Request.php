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
use Longman\TelegramBot\Entities\File;

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
        'getFile'
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
            $data['from'] = array(
                'id' => 123456789,
                'first_name' =>
                'botname',
                'username'=> 'namebot'
            );
            $data['chat'] = array('id'=> $data['chat_id'] );

            $fake_response['result'] = $data;
        }

        return $fake_response;
    }

    public static function executeCurl($action, array $data = null)
    {

        $ch = curl_init();
        if ($ch === false) {
            throw new TelegramException('Curl failed to initialize');
        }

        $curlConfig = array(
            CURLOPT_URL => 'https://api.telegram.org/bot' . self::$telegram->getApiKey() . '/' . $action,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SAFE_UPLOAD => true
        );

        if (!empty($data)) {
            $curlConfig[CURLOPT_POSTFIELDS] = $data;
        }

        if (self::$telegram->getLogVerbosity() >= 3) {
            $curlConfig[CURLOPT_VERBOSE] = true;
            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);
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
        if ($action == 'getUpdates'
            & self::$telegram->getLogVerbosity() >=1
            | self::$telegram->getLogVerbosity() >=3) {
            self::setInputRaw($result);
            self::log($result);
        }

        if ($result === false) {
            throw new TelegramException(curl_error($ch), curl_errno($ch));
        }
        if (empty($result) | is_null($result)) {
            throw new TelegramException('Empty server response');
        }

        curl_close($ch);
        return $result;
    }

    public static function downloadFile(File $file)
    {
        $path = $file->getFilePath();

        #Create the directory
        $basepath = self::$telegram->getDownloadPath();
        $loc_path = $basepath.'/'.$path;

        $dirname = dirname($loc_path);
        if (!is_dir($dirname)) {
            if (!mkdir($dirname, 0755, true)) {
                throw new TelegramException('Directory '.$dirname.' cant be created');
            }
        }
        // open file to write
        $fp = fopen($loc_path, 'w+');
        if ($fp === false) {
            throw new TelegramException('File cant be created');
        }

        $ch = curl_init();
        if ($ch === false) {
            throw new TelegramException('Curl failed to initialize');
        }

        $curlConfig = array(
            CURLOPT_URL => 'https://api.telegram.org/file/bot' . self::$telegram->getApiKey() . '/' . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => 0,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FILE => $fp
        );

        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        if ($result === false) {
            throw new TelegramException(curl_error($ch), curl_errno($ch));
        }
        // close curl
        curl_close($ch);
        // close local file
        fclose($fp);

        if (filesize($loc_path) > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected static function encodeFile($file)
    {
        return  new \CURLFile($file);
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

    public static function forwardMessage(array $data)
    {

        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        $result = self::send('forwardMessage', $data);
        return $result;
    }

    public static function sendPhoto(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['photo'] = self::encodeFile($file);
        }

        $result = self::send('sendPhoto', $data);
        return $result;
    }

    public static function sendAudio(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['audio'] = self::encodeFile($file);
        }

        $result = self::send('sendAudio', $data);
        return $result;
    }

    public static function sendDocument(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['document'] = self::encodeFile($file);
        }

        $result = self::send('sendDocument', $data);
        return $result;
    }

    public static function sendSticker(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['sticker'] = self::encodeFile($file);
        }

        $result = self::send('sendSticker', $data);
        return $result;
    }

    public static function sendVideo(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['video'] = self::encodeFile($file);
        }

        $result = self::send('sendVideo', $data);
        return $result;
    }

    public static function sendVoice(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['voice'] = self::encodeFile($file);
        }

        $result = self::send('sendVoice', $data);
        return $result;
    }
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

    public static function getUserProfilePhotos($data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }
        if (!isset($data['user_id'])) {
            throw new TelegramException('User id is empty!');
        }

        $result = self::send('getUserProfilePhotos', $data);
        return $result;
    }

    public static function getUpdates($data)
    {
        $result = self::send('getUpdates', $data);
        return $result;
    }

    public static function setWebhook($url = '', $file = null)
    {
        $data['url'] = $url;

        if (!is_null($file)) {
            $data['certificate'] = self::encodeFile($file);
        }

        $result = self::send('setWebhook', $data);
        return $result;
    }


    public static function getFile($data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        $result = self::send('getFile', $data);
        return $result;
    }

    /**
     * Send Message in all the active chat
     *
     *
     * @return bool
     */

    public static function sendToActiveChats(
        $callback_function,
        array $data,
        $send_chats = true,
        $send_users = true,
        $date_from = null,
        $date_to = null
    ) {

        $callback_path = __NAMESPACE__ .'\Request';
        if (! method_exists($callback_path, $callback_function)) {
            throw new TelegramException('Methods: '.$callback_function.' not found in class Request.');
        }

        $chats = DB::selectChats($send_chats, $send_users, $date_from, $date_to);

        $results = [];
        foreach ($chats as $row) {
            $data['chat_id'] = $row['chat_id'];
            $results[] = call_user_func_array($callback_path.'::'.$callback_function, array($data));
        }

        return $results;
    }
}
