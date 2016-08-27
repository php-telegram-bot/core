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
     * @var \Longman\TelegramBot\Telegram
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
     * @param \Longman\TelegramBot\Telegram $telegram
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function initialize(Telegram $telegram)
    {
        if (is_object($telegram)) {
            self::$telegram = $telegram;
            self::$client   = new Client(['base_uri' => self::$api_base_uri]);
        } else {
            throw new TelegramException('Telegram pointer is empty!');
        }
    }

    /**
     * Set input from custom input or stdin and return it
     *
     * @return string
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
    public static function generateGeneralFakeServerResponse(array $data = [])
    {
        //PARAM BINDED IN PHPUNIT TEST FOR TestServerResponse.php
        //Maybe this is not the best possible implementation

        //No value set in $data ie testing setWebhook
        //Provided $data['chat_id'] ie testing sendMessage

        $fake_response = ['ok' => true]; // :)

        if ($data === []) {
            $fake_response['result'] = true;
        }

        //some data to let iniatilize the class method SendMessage
        if (isset($data['chat_id'])) {
            $data['message_id'] = '1234';
            $data['date']       = '1441378360';
            $data['from']       = [
                'id'         => 123456789,
                'first_name' => 'botname',
                'username'   => 'namebot',
            ];
            $data['chat']       = ['id' => $data['chat_id']];

            $fake_response['result'] = $data;
        }

        return $fake_response;
    }

    /**
     * Properly set up the request params
     *
     * @param array $data
     *
     * @return array
     */
    private static function setUpRequestParams(array $data)
    {
        //Check for resources in data
        $contains_resource = false;
        foreach ($data as $item) {
            if (is_resource($item)) {
                $contains_resource = true;
                break;
            }
        }

        $request_params = [];
        //Reformat data array in multipart way
        if ($contains_resource) {
            foreach ($data as $key => $item) {
                $request_params['multipart'][] = ['name' => $key, 'contents' => $item];
            }
        } else {
            $request_params['form_params'] = $data;
        }

        return $request_params;
    }

    /**
     * Execute HTTP Request
     *
     * @param string $action Action to execute
     * @param array  $data   Data to attach to the execution
     *
     * @return mixed Result of the HTTP Request
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function execute($action, array $data = [])
    {
        //Fix so that the keyboard markup is a string, not an object
        if (isset($data['reply_markup'])) {
            $data['reply_markup'] = (string)$data['reply_markup'];
        }

        $request_params = self::setUpRequestParams($data);

        $debug_handle            = TelegramLog::getDebugLogTempStream();
        $request_params['debug'] = $debug_handle;

        try {
            $response = self::$client->post(
                '/bot' . self::$telegram->getApiKey() . '/' . $action,
                $request_params
            );
            $result   = (string)$response->getBody();

            //Logging getUpdates Update
            if ($action === 'getUpdates') {
                TelegramLog::update($result);
            }

            return $result;
        } catch (RequestException $e) {
            throw new TelegramException($e->getMessage());
        } finally {
            //Logging verbose debug output
            TelegramLog::endDebugLogTempStream("Verbose HTTP Request output:\n%s\n");
        }
    }

    /**
     * Download file
     *
     * @param \Longman\TelegramBot\Entities\File $file
     *
     * @return boolean
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
            self::$client->get(
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
     * @return resource
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    protected static function encodeFile($file)
    {
        $fp = fopen($file, 'r');
        if ($fp === false) {
            throw new TelegramException('Cannot open ' . $file . ' for reading');
        }

        return $fp;
    }

    /**
     * Send command
     *
     * @todo Fake response doesn't need json encoding?
     *
     * @param string $action
     * @param array  $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function send($action, array $data = [])
    {
        self::ensureValidAction($action);

        $bot_name = self::$telegram->getBotName();

        if (defined('PHPUNIT_TESTSUITE')) {
            $fake_response = self::generateGeneralFakeServerResponse($data);

            return new ServerResponse($fake_response, $bot_name);
        }

        self::ensureNonEmptyData($data);

        $response = json_decode(self::execute($action, $data), true);

        if (null === $response) {
            throw new TelegramException('Telegram returned an invalid response! Please review your bot name and API key.');
        }

        return new ServerResponse($response, $bot_name);
    }

    /**
     * Make sure the data isn't empty, else throw an exception
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private static function ensureNonEmptyData(array $data)
    {
        if (empty($data)) {
            throw new TelegramException('Data is empty!');
        }
    }

    /**
     * Make sure the action is valid, else throw an exception
     *
     * @param string $action
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private static function ensureValidAction($action)
    {
        if (!in_array($action, self::$actions, true)) {
            throw new TelegramException('The action " . $action . " doesn\'t exist!');
        }
    }

    /**
     * Assign an encoded file to a data array
     *
     * @param array  $data
     * @param string $field
     * @param string $file
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private static function assignEncodedFile(&$data, $field, $file)
    {
        if ($file !== null && $file !== '') {
            $data[$field] = self::encodeFile($file);
        }
    }

    /**
     * Get me
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendMessage(array $data)
    {
        $text            = $data['text'];
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function forwardMessage(array $data)
    {
        return self::send('forwardMessage', $data);
    }

    /**
     * Send photo
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendPhoto(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'photo', $file);

        return self::send('sendPhoto', $data);
    }

    /**
     * Send audio
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendAudio(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'audio', $file);

        return self::send('sendAudio', $data);
    }

    /**
     * Send document
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendDocument(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'document', $file);

        return self::send('sendDocument', $data);
    }

    /**
     * Send sticker
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendSticker(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'sticker', $file);

        return self::send('sendSticker', $data);
    }

    /**
     * Send video
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendVideo(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'video', $file);

        return self::send('sendVideo', $data);
    }

    /**
     * Send voice
     *
     * @param array  $data
     * @param string $file
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendVoice(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'voice', $file);

        return self::send('sendVoice', $data);
    }

    /**
     * Send location
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendLocation(array $data)
    {
        return self::send('sendLocation', $data);
    }

    /**
     * Send venue
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendVenue(array $data)
    {
        return self::send('sendVenue', $data);
    }

    /**
     * Send contact
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendContact(array $data)
    {
        return self::send('sendContact', $data);
    }

    /**
     * Send chat action
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendChatAction(array $data)
    {
        return self::send('sendChatAction', $data);
    }

    /**
     * Get user profile photos
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getUserProfilePhotos(array $data)
    {
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function setWebhook($url = '', $file = null)
    {
        $data = ['url' => $url];

        self::assignEncodedFile($data, 'certificate', $file);

        return self::send('setWebhook', $data);
    }

    /**
     * Get file
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getFile(array $data)
    {
        return self::send('getFile', $data);
    }

    /**
     * Kick Chat Member
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function kickChatMember(array $data)
    {
        return self::send('kickChatMember', $data);
    }

    /**
     * Leave Chat
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function leaveChat(array $data)
    {
        return self::send('leaveChat', $data);
    }

    /**
     * Unban Chat Member
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function unbanChatMember(array $data)
    {
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getChat(array $data)
    {
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getChatAdministrators(array $data)
    {
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getChatMembersCount(array $data)
    {
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getChatMember(array $data)
    {
        return self::send('getChatMember', $data);
    }

    /**
     * Answer callback query
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function answerCallbackQuery(array $data)
    {
        return self::send('answerCallbackQuery', $data);
    }

    /**
     * Answer inline query
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function answerInlineQuery(array $data)
    {
        return self::send('answerInlineQuery', $data);
    }

    /**
     * Edit message text
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function editMessageText(array $data)
    {
        return self::send('editMessageText', $data);
    }

    /**
     * Edit message caption
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function editMessageCaption(array $data)
    {
        return self::send('editMessageCaption', $data);
    }

    /**
     * Edit message reply markup
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function editMessageReplyMarkup(array $data)
    {
        return self::send('editMessageReplyMarkup', $data);
    }

    /**
     * Return an empty Server Response
     *
     * No request to telegram are sent, this function is used in commands that
     * don't need to fire a message after execution
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
        if (is_array($chats)) {
            foreach ($chats as $row) {
                $data['chat_id'] = $row['chat_id'];
                $results[]       = call_user_func_array($callback_path . '::' . $callback_function, [$data]);
            }
        }

        return $results;
    }
}
