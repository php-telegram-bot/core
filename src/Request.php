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

/**
 * Class Request
 *
 * @method static ServerResponse getUpdates(array $data)             Use this method to receive incoming updates using long polling (wiki). An Array of Update objects is returned.
 * @method static ServerResponse setWebhook(array $data)             Use this method to specify a url and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified url, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns true.
 * @method static ServerResponse deleteWebhook()                     Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success. Requires no parameters.
 * @method static ServerResponse getWebhookInfo()                    Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 * @method static ServerResponse getMe()                             A simple method for testing your bot's auth token. Requires no parameters. Returns basic information about the bot in form of a User object.
 * @method static ServerResponse forwardMessage(array $data)         Use this method to forward messages of any kind. On success, the sent Message is returned.
 * @method static ServerResponse sendPhoto(array $data)              Use this method to send photos. On success, the sent Message is returned.
 * @method static ServerResponse sendAudio(array $data)              Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .mp3 format. On success, the sent Message is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendDocument(array $data)           Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendSticker(array $data)            Use this method to send .webp stickers. On success, the sent Message is returned.
 * @method static ServerResponse sendVideo(array $data)              Use this method to send video files, Telegram clients support mp4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendVoice(array $data)              Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .ogg file encoded with OPUS (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendVideoNote(array $data)          Use this method to send video messages. On success, the sent Message is returned.
 * @method static ServerResponse sendLocation(array $data)           Use this method to send point on the map. On success, the sent Message is returned.
 * @method static ServerResponse sendVenue(array $data)              Use this method to send information about a venue. On success, the sent Message is returned.
 * @method static ServerResponse sendContact(array $data)            Use this method to send phone contacts. On success, the sent Message is returned.
 * @method static ServerResponse sendChatAction(array $data)         Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success.
 * @method static ServerResponse getUserProfilePhotos(array $data)   Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
 * @method static ServerResponse getFile(array $data)                Use this method to get basic info about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
 * @method static ServerResponse kickChatMember(array $data)         Use this method to kick a user from a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the group on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse unbanChatMember(array $data)        Use this method to unban a previously kicked user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. Returns True on success.
 * @method static ServerResponse restrictChatMember(array $data)     Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate admin rights. Pass True for all boolean parameters to lift restrictions from a user. Returns True on success.
 * @method static ServerResponse promoteChatMember(array $data)      Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Pass False for all boolean parameters to demote a user. Returns True on success.
 * @method static ServerResponse exportChatInviteLink(array $data)   Use this method to export an invite link to a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns exported invite link as String on success.
 * @method static ServerResponse setChatPhoto(array $data)           Use this method to set a new profile photo for the chat. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse deleteChatPhoto(array $data)        Use this method to delete a chat photo. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse setChatTitle(array $data)           Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse setChatDescription(array $data)     Use this method to change the description of a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse pinChatMessage(array $data)         Use this method to pin a message in a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse unpinChatMessage(array $data)       Use this method to unpin a message in a supergroup chat. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse leaveChat(array $data)              Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
 * @method static ServerResponse getChat(array $data)                Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.
 * @method static ServerResponse getChatAdministrators(array $data)  Use this method to get a list of administrators in a chat. On success, returns an Array of ChatMember objects that contains information about all chat administrators except other bots. If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.
 * @method static ServerResponse getChatMembersCount(array $data)    Use this method to get the number of members in a chat. Returns Int on success.
 * @method static ServerResponse getChatMember(array $data)          Use this method to get information about a member of a chat. Returns a ChatMember object on success.
 * @method static ServerResponse answerCallbackQuery(array $data)    Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
 * @method static ServerResponse answerInlineQuery(array $data)      Use this method to send answers to an inline query. On success, True is returned.
 * @method static ServerResponse editMessageText(array $data)        Use this method to edit text and game messages sent by the bot or via the bot (for inline bots). On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static ServerResponse editMessageCaption(array $data)     Use this method to edit captions of messages sent by the bot or via the bot (for inline bots). On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static ServerResponse editMessageReplyMarkup(array $data) Use this method to edit only the reply markup of messages sent by the bot or via the bot (for inline bots). On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static ServerResponse deleteMessage(array $data)          Use this method to delete a message, including service messages, with certain limitations. Returns True on success.
 */
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
     * This is basically the list of all methods listed on the official API documentation.
     *
     * @link https://core.telegram.org/bots/api
     *
     * @var array
     */
    private static $actions = [
        'getUpdates',
        'setWebhook',
        'deleteWebhook',
        'getWebhookInfo',
        'getMe',
        'sendMessage',
        'forwardMessage',
        'sendPhoto',
        'sendAudio',
        'sendDocument',
        'sendSticker',
        'sendVideo',
        'sendVoice',
        'sendVideoNote',
        'sendLocation',
        'sendVenue',
        'sendContact',
        'sendChatAction',
        'getUserProfilePhotos',
        'getFile',
        'kickChatMember',
        'unbanChatMember',
        'restrictChatMember',
        'promoteChatMember',
        'exportChatInviteLink',
        'setChatPhoto',
        'deleteChatPhoto',
        'setChatTitle',
        'setChatDescription',
        'pinChatMessage',
        'unpinChatMessage',
        'leaveChat',
        'getChat',
        'getChatAdministrators',
        'getChatMembersCount',
        'getChatMember',
        'answerCallbackQuery',
        'answerInlineQuery',
        'editMessageText',
        'editMessageCaption',
        'editMessageReplyMarkup',
        'deleteMessage',
    ];

    /**
     * Some methods need a dummy param due to certain cURL issues.
     *
     * @see Request::addDummyParamIfNecessary()
     *
     * @var array
     */
    private static $actions_need_dummy_param = [
        'deleteWebhook',
        'getWebhookInfo',
        'getMe',
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
        if (!is_string($input)) {
            throw new TelegramException('Input must be a string!');
        }

        self::$input = $input;

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
        $multipart    = [];

        // Convert any nested arrays into JSON strings.
        array_walk($data, function (&$item) {
            is_array($item) && $item = json_encode($item);
        });

        //Reformat data array in multipart way if it contains a resource
        foreach ($data as $key => $item) {
            $has_resource |= (is_resource($item) || $item instanceof \GuzzleHttp\Psr7\Stream);
            $multipart[]  = ['name' => $key, 'contents' => $item];
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

        $result                  = null;
        $request_params          = self::setUpRequestParams($data);
        $request_params['debug'] = TelegramLog::getDebugLogTempStream();

        try {
            $response = self::$client->post(
                '/bot' . self::$telegram->getApiKey() . '/' . $action,
                $request_params
            );
            $result   = (string) $response->getBody();

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
    public static function encodeFile($file)
    {
        $fp = fopen($file, 'rb');
        if ($fp === false) {
            throw new TelegramException('Cannot open "' . $file . '" for reading');
        }

        return $fp;
    }

    /**
     * Send command
     *
     * @todo Fake response doesn't need json encoding?
     * @todo Write debug entry on failure
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
        self::addDummyParamIfNecessary($action, $data);

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
     * Add a dummy parameter if the passed action requires it.
     *
     * If a method doesn't require parameters, we need to add a dummy one anyway,
     * because of some cURL version failed POST request without parameters.
     *
     * @link https://github.com/php-telegram-bot/core/pull/228
     *
     * @todo Would be nice to find a better solution for this!
     *
     * @param string $action
     * @param array  $data
     */
    protected static function addDummyParamIfNecessary($action, array &$data)
    {
        if (in_array($action, self::$actions_need_dummy_param, true)) {
            // Can be anything, using a single letter to minimise request size.
            $data = ['d'];
        }
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
     * Any statically called method should be relayed to the `send` method.
     *
     * @param string $action
     * @param array  $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function __callStatic($action, array $data)
    {
        // Make sure to add the action being called as the first parameter to be passed.
        array_unshift($data, $action);

        return call_user_func_array('static::send', $data);
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
            self::$limiter_enabled  = $value;
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
                'sendVideoNote',
                'sendLocation',
                'sendVenue',
                'sendContact',
                'editMessageText',
                'editMessageCaption',
                'editMessageReplyMarkup',
                'setChatTitle',
                'setChatDescription',
            ];

            $chat_id           = isset($data['chat_id']) ? $data['chat_id'] : null;
            $inline_message_id = isset($data['inline_message_id']) ? $data['inline_message_id'] : null;

            if (($chat_id || $inline_message_id) && in_array($action, $limited_methods)) {
                $timeout = 60;

                while (true) {
                    if ($timeout <= 0) {
                        throw new TelegramException('Timed out while waiting for a request spot!');
                    }

                    $requests = DB::getTelegramRequestCount($chat_id, $inline_message_id);

                    $chat_per_second   = ($requests['LIMIT_PER_SEC'] == 0); // No more than one message per second inside a particular chat
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
}
