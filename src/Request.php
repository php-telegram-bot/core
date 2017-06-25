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
     * Request limiter
     *
     * @var boolean
     */
    private static $limiter_enabled;

    /**
     * Request limiter's interval between checks
     *
     * @var boolean
     */
    private static $limiter_interval;

    /**
     * Available actions to send
     *
     * @var array
     */
    private static $actions = [
        'getUpdates',
        'setWebhook',
        'deleteWebhook',
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
        'getWebhookInfo',
        'deleteMessage',
    ];

    /**
     * Initialize
     *
     * @param \Longman\TelegramBot\Telegram $telegram
     *
     * @throws TelegramException
     */
    public static function initialize(Telegram $telegram)
    {
        if (!($telegram instanceof Telegram)) {
            throw new TelegramException('Invalid Telegram pointer!');
        }

        self::$telegram = $telegram;
        self::setClient(new Client(['base_uri' => self::$api_base_uri]));
    }

    /**
     * Set a custom Guzzle HTTP Client object
     *
     * @param Client $client
     *
     * @throws TelegramException
     */
    public static function setClient(Client $client)
    {
        if (!($client instanceof Client)) {
            throw new TelegramException('Invalid GuzzleHttp\Client pointer!');
        }

        self::$client = $client;
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
     * If any item of the array is a resource, reformat it to a multipart request.
     * Else, just return the passed data as form params.
     *
     * @param array $data
     *
     * @return array
     */
    private static function setUpRequestParams(array $data)
    {
        $has_resource = false;
        $multipart = [];

        // Convert any nested arrays into JSON strings.
        array_walk($data, function (&$item) {
            is_array($item) && $item = json_encode($item);
        });

        //Reformat data array in multipart way if it contains a resource
        foreach ($data as $key => $item) {
            $has_resource |= (is_resource($item) || $item instanceof \GuzzleHttp\Psr7\Stream);
            $multipart[] = ['name' => $key, 'contents' => $item];
        }
        if ($has_resource) {
            return ['multipart' => $multipart];
        }

        return ['form_params' => $data];
    }

    /**
     * Execute HTTP Request
     *
     * @param string $action Action to execute
     * @param array  $data   Data to attach to the execution
     *
     * @return string Result of the HTTP Request
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function execute($action, array $data = [])
    {
        //Fix so that the keyboard markup is a string, not an object
        if (isset($data['reply_markup'])) {
            $data['reply_markup'] = json_encode($data['reply_markup']);
        }

        $result = null;
        $request_params = self::setUpRequestParams($data);
        $request_params['debug'] = TelegramLog::getDebugLogTempStream();

        try {
            $response = self::$client->post(
                '/bot' . self::$telegram->getApiKey() . '/' . $action,
                $request_params
            );
            $result = (string) $response->getBody();

            //Logging getUpdates Update
            if ($action === 'getUpdates') {
                TelegramLog::update($result);
            }
        } catch (RequestException $e) {
            $result = ($e->getResponse()) ? (string) $e->getResponse()->getBody() : '';
        } finally {
            //Logging verbose debug output
            TelegramLog::endDebugLogTempStream('Verbose HTTP Request output:' . PHP_EOL . '%s' . PHP_EOL);
        }

        return $result;
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
        if (empty($download_path = self::$telegram->getDownloadPath())) {
            throw new TelegramException('Download path not set!');
        }

        $tg_file_path = $file->getFilePath();
        $file_path    = $download_path . '/' . $tg_file_path;

        $file_dir = dirname($file_path);
        //For safety reasons, first try to create the directory, then check that it exists.
        //This is in case some other process has created the folder in the meantime.
        if (!@mkdir($file_dir, 0755, true) && !is_dir($file_dir)) {
            throw new TelegramException('Directory ' . $file_dir . ' can\'t be created');
        }

        $debug_handle = TelegramLog::getDebugLogTempStream();

        try {
            self::$client->get(
                '/file/bot' . self::$telegram->getApiKey() . '/' . $tg_file_path,
                ['debug' => $debug_handle, 'sink' => $file_path]
            );

            return filesize($file_path) > 0;
        } catch (RequestException $e) {
            return ($e->getResponse()) ? (string) $e->getResponse()->getBody() : '';
        } finally {
            //Logging verbose debug output
            TelegramLog::endDebugLogTempStream('Verbose HTTP File Download Request output:' . PHP_EOL . '%s' . PHP_EOL);
        }
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
            throw new TelegramException('Cannot open "' . $file . '" for reading');
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

        $bot_username = self::$telegram->getBotUsername();

        if (defined('PHPUNIT_TESTSUITE')) {
            $fake_response = self::generateGeneralFakeServerResponse($data);

            return new ServerResponse($fake_response, $bot_username);
        }

        self::ensureNonEmptyData($data);

        self::limitTelegramRequests($action, $data);

        $response = json_decode(self::execute($action, $data), true);

        if (null === $response) {
            throw new TelegramException('Telegram returned an invalid response! Please review your bot name and API key.');
        }

        return new ServerResponse($response, $bot_username);
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
        if (count($data) === 0) {
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
            throw new TelegramException('The action "' . $action . '" doesn\'t exist!');
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
     * Returns basic information about the bot in form of a User object
     *
     * @link https://core.telegram.org/bots/api#getme
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getMe()
    {
        // Added fake parameter, because of some cURL version failed POST request without parameters
        // see https://github.com/php-telegram-bot/core/pull/228
        return self::send('getMe', ['whoami']);
    }

    /**
     * Use this method to send text messages. On success, the sent Message is returned
     *
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendMessage(array $data)
    {
        $text = $data['text'];

        do {
            //Chop off and send the first message
            $data['text'] = mb_substr($text, 0, 4096);
            $response     = self::send('sendMessage', $data);

            //Prepare the next message
            $text = mb_substr($text, 4096);
        } while (mb_strlen($text, 'UTF-8') > 0);

        return $response;
    }

    /**
     * Use this method to forward messages of any kind. On success, the sent Message is returned
     *
     * @link https://core.telegram.org/bots/api#forwardmessage
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function forwardMessage(array $data)
    {
        return self::send('forwardMessage', $data);
    }

    /**
     * Use this method to send photos. On success, the sent Message is returned
     *
     * @link https://core.telegram.org/bots/api#sendphoto
     *
     * @param array  $data
     * @param string $file
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendPhoto(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'photo', $file);

        return self::send('sendPhoto', $data);
    }

    /**
     * Use this method to send audio files
     *
     * Your audio must be in the .mp3 format. On success, the sent Message is returned.
     * Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future.
     * For sending voice messages, use the sendVoice method instead.
     *
     * @link https://core.telegram.org/bots/api#sendaudio
     *
     * @param array  $data
     * @param string $file
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendAudio(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'audio', $file);

        return self::send('sendAudio', $data);
    }

    /**
     * Use this method to send general files. On success, the sent Message is returned.
     *
     * Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
     *
     * @link https://core.telegram.org/bots/api#senddocument
     *
     * @param array  $data
     * @param string $file
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendDocument(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'document', $file);

        return self::send('sendDocument', $data);
    }

    /**
     * Use this method to send .webp stickers. On success, the sent Message is returned.
     *
     * @link https://core.telegram.org/bots/api#sendsticker
     *
     * @param array  $data
     * @param string $file
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendSticker(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'sticker', $file);

        return self::send('sendSticker', $data);
    }

    /**
     * Use this method to send video files. On success, the sent Message is returned.
     *
     * Telegram clients support mp4 videos (other formats may be sent as Document).
     * Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
     *
     * @link https://core.telegram.org/bots/api#sendvideo
     *
     * @param array  $data
     * @param string $file
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendVideo(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'video', $file);

        return self::send('sendVideo', $data);
    }

    /**
     * Use this method to send audio files. On success, the sent Message is returned.
     *
     * Telegram clients will display the file as a playable voice message.
     * For this to work, your audio must be in an .ogg file encoded with OPUS (other formats may be sent as Audio or Document).
     * Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
     *
     * @link https://core.telegram.org/bots/api#sendvoice
     *
     * @param array  $data
     * @param string $file
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendVoice(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'voice', $file);

        return self::send('sendVoice', $data);
    }

    /**
     * Use this method to send point on the map. On success, the sent Message is returned.
     *
     * @link https://core.telegram.org/bots/api#sendlocation
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendLocation(array $data)
    {
        return self::send('sendLocation', $data);
    }

    /**
     * Use this method to send information about a venue. On success, the sent Message is returned.
     *
     * @link https://core.telegram.org/bots/api#sendvenue
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendVenue(array $data)
    {
        return self::send('sendVenue', $data);
    }

    /**
     * Use this method to send phone contacts. On success, the sent Message is returned.
     *
     * @link https://core.telegram.org/bots/api#sendcontact
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendContact(array $data)
    {
        return self::send('sendContact', $data);
    }

    /**
     * Use this method when you need to tell the user that something is happening on the bot's side.
     *
     * The status is set for 5 seconds or less.
     * (when a message arrives from your bot, Telegram clients clear its typing status)
     *
     * @link https://core.telegram.org/bots/api#sendchataction
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendChatAction(array $data)
    {
        return self::send('sendChatAction', $data);
    }

    /**
     * Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getUserProfilePhotos(array $data)
    {
        return self::send('getUserProfilePhotos', $data);
    }

    /**
     * Use this method to get basic info about a file and prepare it for downloading. On success, a File object is returned.
     *
     * For the moment, bots can download files of up to 20MB in size.
     * The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>,
     * where <file_path> is taken from the response.
     * It is guaranteed that the link will be valid for at least 1 hour.
     * When the link expires, a new one can be requested by calling getFile again.
     *
     * @link https://core.telegram.org/bots/api#getfile
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getFile(array $data)
    {
        return self::send('getFile', $data);
    }

    /**
     * Use this method to kick a user from a group or a supergroup. Returns True on success.
     *
     * In the case of supergroups, the user will not be able to return to the group on their own using invite links, etc., unless unbanned first.
     * The bot must be an administrator in the group for this to work.
     *
     * @link https://core.telegram.org/bots/api#kickchatmember
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function kickChatMember(array $data)
    {
        return self::send('kickChatMember', $data);
    }

    /**
     * Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
     *
     * @link https://core.telegram.org/bots/api#leavechat
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function leaveChat(array $data)
    {
        return self::send('leaveChat', $data);
    }

    /**
     * Use this method to unban a previously kicked user in a supergroup. Returns True on success.
     *
     * The user will not return to the group automatically, but will be able to join via link, etc.
     * The bot must be an administrator in the group for this to work.
     *
     * @link https://core.telegram.org/bots/api#unbanchatmember
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function unbanChatMember(array $data)
    {
        return self::send('unbanChatMember', $data);
    }

    /**
     * Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.
     *
     * @todo add get response in ServerResponse.php?
     *
     * @link https://core.telegram.org/bots/api#getchat
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getChat(array $data)
    {
        return self::send('getChat', $data);
    }

    /**
     * Use this method to get a list of administrators in a chat.
     *
     * On success, returns an Array of ChatMember objects that contains information about all chat administrators except other bots.
     * If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.
     *
     * @todo add get response in ServerResponse.php?
     *
     * @link https://core.telegram.org/bots/api#getchatadministrators
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getChatAdministrators(array $data)
    {
        return self::send('getChatAdministrators', $data);
    }

    /**
     * Use this method to get the number of members in a chat. Returns Int on success.
     *
     * @todo add get response in ServerResponse.php?
     *
     * @link https://core.telegram.org/bots/api#getchatmemberscount
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getChatMembersCount(array $data)
    {
        return self::send('getChatMembersCount', $data);
    }

    /**
     * Use this method to get information about a member of a chat. Returns a ChatMember object on success.
     *
     * @todo add get response in ServerResponse.php?
     *
     * @link https://core.telegram.org/bots/api#getchatmember
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getChatMember(array $data)
    {
        return self::send('getChatMember', $data);
    }

    /**
     * Use this method to send answers to callback queries sent from inline keyboards. On success, True is returned.
     *
     * The answer will be displayed to the user as a notification at the top of the chat screen or as an alert.
     *
     * @link https://core.telegram.org/bots/api#answercallbackquery
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function answerCallbackQuery(array $data)
    {
        return self::send('answerCallbackQuery', $data);
    }

    /**
     * Get updates
     *
     * @link https://core.telegram.org/bots/api#getupdates
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getUpdates(array $data)
    {
        return self::send('getUpdates', $data);
    }

    /**
     * Set webhook
     *
     * @link https://core.telegram.org/bots/api#setwebhook
     *
     * @param string $url
     * @param array  $data Optional parameters.
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function setWebhook($url = '', array $data = [])
    {
        $data        = array_intersect_key($data, array_flip([
            'certificate',
            'max_connections',
            'allowed_updates',
        ]));
        $data['url'] = $url;

        if (isset($data['certificate'])) {
            self::assignEncodedFile($data, 'certificate', $data['certificate']);
        }

        return self::send('setWebhook', $data);
    }

    /**
     * Delete webhook
     *
     * @link https://core.telegram.org/bots/api#deletewebhook
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function deleteWebhook()
    {
        // Must send some arbitrary data for this to work for now...
        return self::send('deleteWebhook', ['delete']);
    }

    /**
     * Use this method to edit text and game messages sent by the bot or via the bot (for inline bots).
     *
     * On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
     *
     * @link https://core.telegram.org/bots/api#editmessagetext
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function editMessageText(array $data)
    {
        return self::send('editMessageText', $data);
    }

    /**
     * Use this method to edit captions of messages sent by the bot or via the bot (for inline bots).
     *
     * On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
     *
     * @link https://core.telegram.org/bots/api#editmessagecaption
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function editMessageCaption(array $data)
    {
        return self::send('editMessageCaption', $data);
    }

    /**
     * Use this method to edit only the reply markup of messages sent by the bot or via the bot (for inline bots).
     *
     * On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
     *
     * @link https://core.telegram.org/bots/api#editmessagereplymarkup
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function editMessageReplyMarkup(array $data)
    {
        return self::send('editMessageReplyMarkup', $data);
    }

    /**
     * Use this method to send answers to an inline query. On success, True is returned.
     *
     * No more than 50 results per query are allowed.
     *
     * @link https://core.telegram.org/bots/api#answerinlinequery
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function answerInlineQuery(array $data)
    {
        return self::send('answerInlineQuery', $data);
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
     * @param string $callback_function
     * @param array  $data
     * @param array  $select_chats_params
     *
     * @return array
     * @throws TelegramException
     */
    public static function sendToActiveChats(
        $callback_function,
        array $data,
        array $select_chats_params
    ) {
        $callback_path = __NAMESPACE__ . '\Request';
        if (!method_exists($callback_path, $callback_function)) {
            throw new TelegramException('Method "' . $callback_function . '" not found in class Request.');
        }

        $chats = DB::selectChats($select_chats_params);

        $results = [];
        if (is_array($chats)) {
            foreach ($chats as $row) {
                $data['chat_id'] = $row['chat_id'];
                $results[]       = call_user_func($callback_path . '::' . $callback_function, $data);
            }
        }

        return $results;
    }

    /**
     * Use this method to get current webhook status.
     *
     * @link https://core.telegram.org/bots/api#getwebhookinfo
     *
     * @return Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function getWebhookInfo()
    {
        // Must send some arbitrary data for this to work for now...
        return self::send('getWebhookInfo', ['info']);
    }

    /**
     * Enable request limiter
     *
     * @param boolean $value
     * @param array   $options
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function setLimiter($value = true, array $options = [])
    {
        if (DB::isDbConnected()) {
            $options_default = [
                'interval' => 1,
            ];

            $options = array_merge($options_default, $options);

            if (!is_numeric($options['interval']) || $options['interval'] <= 0) {
                throw new TelegramException('Interval must be a number and must be greater than zero!');
            }

            self::$limiter_interval = $options['interval'];
            self::$limiter_enabled = $value;
        }
    }

    /**
     * This functions delays API requests to prevent reaching Telegram API limits
     *  Can be disabled while in execution by 'Request::setLimiter(false)'
     *
     * @link https://core.telegram.org/bots/faq#my-bot-is-hitting-limits-how-do-i-avoid-this
     *
     * @param string $action
     * @param array  $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private static function limitTelegramRequests($action, array $data = [])
    {
        if (self::$limiter_enabled) {
            $limited_methods = [
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
                'editMessageText',
                'editMessageCaption',
                'editMessageReplyMarkup',
            ];

            $chat_id = isset($data['chat_id']) ? $data['chat_id'] : null;
            $inline_message_id = isset($data['inline_message_id']) ? $data['inline_message_id'] : null;

            if (($chat_id || $inline_message_id) && in_array($action, $limited_methods)) {
                $timeout = 60;

                while (true) {
                    if ($timeout <= 0) {
                        throw new TelegramException('Timed out while waiting for a request spot!');
                    }

                    $requests = DB::getTelegramRequestCount($chat_id, $inline_message_id);

                    $chat_per_second = ($requests['LIMIT_PER_SEC'] == 0); // No more than one message per second inside a particular chat
                    $global_per_second = ($requests['LIMIT_PER_SEC_ALL'] < 30);    // No more than 30 messages per second to different chats
                    $groups_per_minute = (((is_numeric($chat_id) && $chat_id > 0) || !is_null($inline_message_id)) || ((!is_numeric($chat_id) || $chat_id < 0) && $requests['LIMIT_PER_MINUTE'] < 20));    // No more than 20 messages per minute in groups and channels

                    if ($chat_per_second && $global_per_second && $groups_per_minute) {
                        break;
                    }

                    $timeout--;
                    usleep(self::$limiter_interval * 1000000);
                }

                DB::insertTelegramRequest($action, $data);
            }
        }
    }

    /**
     * Use this method to delete either bot's messages or messages of other users if the bot is admin of the group.
     *
     * On success, true is returned.
     *
     * @link https://core.telegram.org/bots/api#deletemessage
     *
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function deleteMessage(array $data)
    {
        return self::send('deleteMessage', $data);
    }

    /**
     * Use this method to send video notes. On success, the sent Message is returned.
     *
     * @link https://core.telegram.org/bots/api#sendvideonote
     *
     * @param array  $data
     * @param string $file
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function sendVideoNote(array $data, $file = null)
    {
        self::assignEncodedFile($data, 'video_note', $file);

        return self::send('sendVideoNote', $data);
    }
}
