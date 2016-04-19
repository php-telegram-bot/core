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

use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class Request
{
    /**
     * Telegram object
     *
     * @var Telegram
     */
    private static $telegram;

    /**
     * Input value of the request
     *
     * @var string
     */
    private static $input;

    /**
     * Available methods to request
     *
     * @todo Possibly rename to "actions"?
     *
     * @var array
     */
    private static $methods = [
        'getUpdates',
        'setWebhook',
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
        'sendVenue',
        'sendContact',
        'sendChatAction',
        'getUserProfilePhotos',
        'getFile',
        'kickChatMember',
        'unbanChatMember',
        'answerCallbackQuery',
        'answerInlineQuery',
        'editMessageText',
        'editMessageCaption',
        'editMessageReplyMarkup'
    ];

    /**
     * Initialize
     *
     * @param Telegram $telegram
     */
    public static function initialize(Telegram $telegram)
    {
        if (is_object($telegram)) {
            self::$telegram = $telegram;
        } else {
            throw new TelegramException('Telegram pointer is empty!');
        }
    }

    /**
     * Set raw input data string
     *
     * @todo Possibly set this to private, since getInput overwrites the input anyway
     * @todo Why the "| $input == false"?
     *
     * @param string $input
     */
    public static function setInputRaw($input)
    {
        if (is_string($input) | $input == false) {
            self::$input = $input;
        } else {
            throw new TelegramException('Input must be a string!');
        }
    }

    /**
     * Set input from custom input or stdin and return it
     *
     * @return string
     */
    public static function getInput()
    {
        if ($input = self::$telegram->getCustomInput()) {
            self::setInputRaw($input);
        } else {
            self::setInputRaw(file_get_contents('php://input'));
        }
        self::log(self::$input);
        return self::$input;
    }

    /**
     * Write log entry
     *
     * @todo Take log verbosity into account
     *
     * @param string $string
     *
     * @return mixed
     */
    private static function log($string)
    {
        if (!self::$telegram->getLogRequests()) {
            return false;
        }

        $path = self::$telegram->getLogPath();
        if (!$path) {
            return false;
        }

        return file_put_contents($path, $string . "\n", FILE_APPEND);
    }

    /**
     * Generate general fake server response
     *
     * @param array $data Data to add to fake response
     *
     * @return array Fake response data
     */
    public static function generateGeneralFakeServerResponse(array $data = null)
    {
        //PARAM BINDED IN PHPUNIT TEST FOR TestServerResponse.php
        //Maybe this is not the best possible implementation

        //No value set in $data ie testing setWebhook
        //Provided $data['chat_id'] ie testing sendMessage

        $fake_response = ['ok' => true]; // :)

        if (!isset($data)) {
            $fake_response['result'] = true;
        }

        //some data to let iniatilize the class method SendMessage
        if (isset($data['chat_id'])) {
            $data['message_id'] = '1234';
            $data['date'] = '1441378360';
            $data['from'] = [
                'id'         => 123456789,
                'first_name' => 'botname',
                'username'   => 'namebot',
            ];
            $data['chat'] = ['id' => $data['chat_id']];

            $fake_response['result'] = $data;
        }

        return $fake_response;
    }

    /**
     * Execute cURL call
     *
     * @param string     $action Action to execute
     * @param array|null $data   Data to attach to the execution
     *
     * @return mixed Result of the cURL call
     */
    public static function executeCurl($action, array $data = null)
    {
        $ch = curl_init();
        if ($ch === false) {
            throw new TelegramException('Curl failed to initialize');
        }

        $curlConfig = [
            CURLOPT_URL            => 'https://api.telegram.org/bot' . self::$telegram->getApiKey() . '/' . $action,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SAFE_UPLOAD    => true,
        ];

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
            self::log('Verbose curl output:' . "\n" . htmlspecialchars($verboseLog) . "\n");
        }

        //Logging getUpdates Update
        //Logging curl updates
        if ($action == 'getUpdates' & self::$telegram->getLogVerbosity() >= 1 | self::$telegram->getLogVerbosity() >= 3
        ) {
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

    /**
     * Download file
     *
     * @param Entities\File $file
     *
     * @return boolean
     */
    public static function downloadFile(File $file)
    {
        $path = $file->getFilePath();

        //Create the directory
        $basepath = self::$telegram->getDownloadPath();
        $loc_path = $basepath . '/' . $path;

        $dirname = dirname($loc_path);
        if (!is_dir($dirname)) {
            if (!mkdir($dirname, 0755, true)) {
                throw new TelegramException('Directory ' . $dirname . ' can\'t be created');
            }
        }
        //Open file to write
        $fp = fopen($loc_path, 'w+');
        if ($fp === false) {
            throw new TelegramException('File can\'t be created');
        }

        $ch = curl_init();
        if ($ch === false) {
            throw new TelegramException('Curl failed to initialize');
        }

        $curlConfig = [
            CURLOPT_URL            => 'https://api.telegram.org/file/bot' . self::$telegram->getApiKey() . '/' . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => 0,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FILE           => $fp,
        ];

        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        if ($result === false) {
            throw new TelegramException(curl_error($ch), curl_errno($ch));
        }

        //Close curl
        curl_close($ch);
        //Close local file
        fclose($fp);

        return (filesize($loc_path) > 0);
    }

    /**
     * Encode file
     *
     * @param string $file
     *
     * @return CURLFile
     */
    protected static function encodeFile($file)
    {
        return new \CURLFile($file);
    }

    /**
     * Send command
     *
     * @todo Fake response doesn't need json encoding?
     * @todo Rename "methods" to "actions"
     *
     * @param string     $action
     * @param array|null $data
     *
     * @return Entities\ServerResponse
     */
    public static function send($action, array $data = null)
    {
        if (!in_array($action, self::$methods)) {
            throw new TelegramException('This method doesn\'t exist!');
        }

        $bot_name = self::$telegram->getBotName();

        if (defined('PHPUNIT_TESTSUITE')) {
            $fake_response = self::generateGeneralFakeServerResponse($data);
            return new ServerResponse($fake_response, $bot_name);
        }

        $response = json_decode(self::executeCurl($action, $data), true);

        if (is_null($response)) {
            throw new TelegramException('Telegram returned an invalid response! Please your bot name and api token.');
        }

        return new ServerResponse($response, $bot_name);
    }

    /**
     * Get me
     *
     * @return mixed
     */
    public static function getMe()
    {
        return self::send('getMe');
    }

    /**
     * Send message
     *
     * @todo Could do with some cleaner recursion
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function sendMessage(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }
        $text = $data['text'];
        $string_len_utf8 = mb_strlen($text, 'UTF-8');
        if ($string_len_utf8 > 4096) {
            $data['text'] = mb_substr($text, 0, 4096);
            $result = self::send('sendMessage', $data);
            $data['text'] = mb_substr($text, 4096, $string_len_utf8);
            return self::sendMessage($data);
        }
        return self::send('sendMessage', $data);
    }

    /**
     * Forward message
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function forwardMessage(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('forwardMessage', $data);
    }

    /**
     * Send photo
     *
     * @param array $data
     * @param string $file
     *
     * @return mixed
     */
    public static function sendPhoto(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['photo'] = self::encodeFile($file);
        }

        return self::send('sendPhoto', $data);
    }

    /**
     * Send audio
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     */
    public static function sendAudio(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['audio'] = self::encodeFile($file);
        }

        return self::send('sendAudio', $data);
    }

    /**
     * Send document
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     */
    public static function sendDocument(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['document'] = self::encodeFile($file);
        }

        return self::send('sendDocument', $data);
    }

    /**
     * Send sticker
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     */
    public static function sendSticker(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['sticker'] = self::encodeFile($file);
        }

        return self::send('sendSticker', $data);
    }

    /**
     * Send video
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     */
    public static function sendVideo(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['video'] = self::encodeFile($file);
        }

        return self::send('sendVideo', $data);
    }

    /**
     * Send voice
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     */
    public static function sendVoice(array $data, $file = null)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!is_null($file)) {
            $data['voice'] = self::encodeFile($file);
        }

        return self::send('sendVoice', $data);
    }

    /**
     * Send location
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function sendLocation(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('sendLocation', $data);
    }

    /**
     * Send venue
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function sendVenue(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('sendVenue', $data);
    }

    /**
     * Send contact
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function sendContact(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('sendContact', $data);
    }

    /**
     * Send chat action
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function sendChatAction(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('sendChatAction', $data);
    }

    /**
     * Get user profile photos
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function getUserProfilePhotos(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        if (!isset($data['user_id'])) {
            throw new TelegramException('User id is empty!');
        }

        return self::send('getUserProfilePhotos', $data);
    }

    /**
     * Get updates
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function getUpdates(array $data)
    {
        return self::send('getUpdates', $data);
    }

    /**
     * Set webhook
     *
     * @param string $url
     * @param string $file
     *
     * @return mixed
     */
    public static function setWebhook($url = '', $file = null)
    {
        $data = ['url' => $url];

        if (!is_null($file)) {
            $data['certificate'] = self::encodeFile($file);
        }

        return self::send('setWebhook', $data);
    }

    /**
     * Get file
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function getFile(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('getFile', $data);
    }

    /**
     * Kick Chat Member
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function kickChatMember(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('kickChatMember', $data);
    }

    /**
     * Unban Chat Member
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function unbanChatMember(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('unbanChatMember', $data);
    }

    /**
     * Answer callback query
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function answerCallbackQuery(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('answerCallbackQuery', $data);
    }

    /**
     * Answer inline query
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function answerInlineQuery(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('answerInlineQuery', $data);
    }

    /**
     * Edit message text
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function editMessageText(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('editMessageText', $data);
    }

    /**
     * Edit message caption
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function editMessageCaption(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('editMessageCaption', $data);
    }

    /**
     * Edit message reply markup
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function editMessageReplyMarkup(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('editMessageReplyMarkup', $data);
    }

    /**
     * Return an empty Server Response
     *
     * No request to telegram are sent, this function is used in commands that
     * don't need to fire a message after execution
     *
     * @return Entities\ServerResponse
     */
    public static function emptyResponse()
    {
        return new ServerResponse(['ok' => true, 'result' => true], null);
    }

    /**
     * Send message to all active chats
     *
     * @param string  $callback_function
     * @param array   $data
     * @param boolean $send_groups
     * @param boolean $send_super_groups
     * @param boolean $send_users
     * @param string  $date_from
     * @param string  $date_to
     *
     * @return array
     */
    public static function sendToActiveChats(
        $callback_function,
        array $data,
        $send_groups = true,
        $send_super_groups = true,
        $send_users = true,
        $date_from = null,
        $date_to = null
    ) {
        $callback_path = __NAMESPACE__ . '\Request';
        if (!method_exists($callback_path, $callback_function)) {
            throw new TelegramException('Method "' . $callback_function . '" not found in class Request.');
        }

        $chats = DB::selectChats($send_groups, $send_super_groups, $send_users, $date_from, $date_to);

        $results = [];
        foreach ($chats as $row) {
            $data['chat_id'] = $row['chat_id'];
            $results[] = call_user_func_array($callback_path . '::' . $callback_function, [$data]);
        }

        return $results;
    }
}
