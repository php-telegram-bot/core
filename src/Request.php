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
     * URI of the Telegram API
     *
     * @var string
     */
    private static $api_base_uri = 'https://api.telegram.org';

    /**
     * Guzzle Client object
     *
     * @var \GuzzleHttp\Client
     */
    private static $client;

    /**
     * Input value of the request
     *
     * @var string
     */
    private static $input;

    /**
     * Available actions to send
     *
     * @var array
     */
    private static $actions = [
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
        'leaveChat',
        'unbanChatMember',
        'getChat',
        'getChatAdministrators',
        'getChatMember',
        'getChatMembersCount',
        'answerCallbackQuery',
        'answerInlineQuery',
        'editMessageText',
        'editMessageCaption',
        'editMessageReplyMarkup',
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
            self::$client = new Client(['base_uri' => self::$api_base_uri]);
        } else {
            throw new TelegramException('Telegram pointer is empty!');
        }
    }

    /**
     * Set input from custom input or stdin and return it
     *
     * @return string
     */
    public static function getInput()
    {
        // First check if a custom input has been set, else get the PHP input.
        if (!($input = self::$telegram->getCustomInput())) {
            $input = file_get_contents('php://input');
        }

        // Make sure we have a string to work with.
        if (is_string($input)) {
            self::$input = $input;
        } else {
            throw new TelegramException('Input must be a string!');
        }

        TelegramLog::update(self::$input);
        return self::$input;
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
     * Execute HTTP Request
     *
     * @param string     $action Action to execute
     * @param array|null $data   Data to attach to the execution
     *
     * @return mixed Result of the HTTP Request
     */
    public static function execute($action, array $data = null)
    {
        $debug_handle = TelegramLog::getDebugLogTempStream();

        try {
            //Fix so that the keyboard markup is a string, not an object
            if (isset($data['reply_markup']) && !is_string($data['reply_markup'])) {
                $data['reply_markup'] = (string)$data['reply_markup'];
            }

            $response = self::$client->post(
                '/bot' . self::$telegram->getApiKey() . '/' . $action,
                ['debug' => $debug_handle, 'form_params' => $data]
            );
        } catch (RequestException $e) {
            throw new TelegramException($e->getMessage());
        } finally {
            //Logging verbose debug output
            TelegramLog::endDebugLogTempStream("Verbose HTTP Request output:\n%s\n");
        }

        $result = $response->getBody();

        //Logging getUpdates Update
        if ($action === 'getUpdates') {
            TelegramLog::update($result);
        }

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
        $loc_path = self::$telegram->getDownloadPath() . '/' . $path;

        $dirname = dirname($loc_path);
        if (!is_dir($dirname) && !mkdir($dirname, 0755, true)) {
            throw new TelegramException('Directory ' . $dirname . ' can\'t be created');
        }

        $debug_handle = TelegramLog::getDebugLogTempStream();

        try {
            $response = self::$client->get(
                '/file/bot' . self::$telegram->getApiKey() . '/' . $path,
                ['debug' => $debug_handle, 'sink' => $loc_path]
            );
        } catch (RequestException $e) {
            throw new TelegramException($e->getMessage());
        } finally {
            //Logging verbose debug output
            TelegramLog::endDebugLogTempStream("Verbose HTTP File Download Request output:\n%s\n");
        }

        return (filesize($loc_path) > 0);
    }

    /**
     * Encode file
     *
     * @param string $file
     *
     * @return \CURLFile
     */
    protected static function encodeFile($file)
    {
        return new \CURLFile($file);
    }

    /**
     * Send command
     *
     * @todo Fake response doesn't need json encoding?
     *
     * @param string     $action
     * @param array|null $data
     *
     * @return Entities\ServerResponse
     */
    public static function send($action, array $data = null)
    {
        if (!in_array($action, self::$actions)) {
            throw new TelegramException('The action ' . $action . ' doesn\'t exist!');
        }

        $bot_name = self::$telegram->getBotName();

        if (defined('PHPUNIT_TESTSUITE')) {
            $fake_response = self::generateGeneralFakeServerResponse($data);
            return new ServerResponse($fake_response, $bot_name);
        }

        $response = json_decode(self::execute($action, $data), true);

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
        // Added fake parameter, because of some cURL version failed POST request without parameters
        // see https://github.com/akalongman/php-telegram-bot/pull/228
        return self::send('getMe', ['whoami']);
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
            self::send('sendMessage', $data);
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
     * Leave Chat
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function leaveChat(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('leaveChat', $data);
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
     * Get Chat
     *
     * @todo add get response in ServerResponse.php?
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function getChat(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('getChat', $data);
    }

    /**
     * Get Chat Administrators
     *
     * @todo add get response in ServerResponse.php?
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function getChatAdministrators(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('getChatAdministrators', $data);
    }

    /**
     * Get Chat Members Count
     *
     * @todo add get response in ServerResponse.php?
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function getChatMembersCount(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('getChatMembersCount', $data);
    }

    /**
     * Get Chat Member
     *
     * @todo add get response in ServerResponse.php?
     *
     * @param array $data
     *
     * @return mixed
     */
    public static function getChatMember(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }

        return self::send('getChatMember', $data);
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
