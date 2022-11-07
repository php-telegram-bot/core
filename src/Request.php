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
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Stream;
use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Entities\InputMedia\InputMedia;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\InvalidBotTokenException;
use Longman\TelegramBot\Exception\TelegramException;
use Throwable;

/**
 * Class Request
 *
 * @method static ServerResponse getUpdates(array $data)                      Use this method to receive incoming updates using long polling (wiki). An Array of Update objects is returned.
 * @method static ServerResponse setWebhook(array $data)                      Use this method to specify a url and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified url, containing a JSON-serialized Update. In case of an unsuccessful request, we will give up after a reasonable amount of attempts. Returns true.
 * @method static ServerResponse deleteWebhook(array $data)                   Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
 * @method static ServerResponse getWebhookInfo()                             Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
 * @method static ServerResponse getMe()                                      A simple method for testing your bot's auth token. Requires no parameters. Returns basic information about the bot in form of a User object.
 * @method static ServerResponse logOut()                                     Use this method to log out from the cloud Bot API server before launching the bot locally. Requires no parameters. Returns True on success.
 * @method static ServerResponse close()                                      Use this method to close the bot instance before moving it from one local server to another. Requires no parameters. Returns True on success.
 * @method static ServerResponse forwardMessage(array $data)                  Use this method to forward messages of any kind. On success, the sent Message is returned.
 * @method static ServerResponse copyMessage(array $data)                     Use this method to copy messages of any kind. The method is analogous to the method forwardMessages, but the copied message doesn't have a link to the original message. Returns the MessageId of the sent message on success.
 * @method static ServerResponse sendPhoto(array $data)                       Use this method to send photos. On success, the sent Message is returned.
 * @method static ServerResponse sendAudio(array $data)                       Use this method to send audio files, if you want Telegram clients to display them in the music player. Your audio must be in the .mp3 format. On success, the sent Message is returned. Bots can currently send audio files of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendDocument(array $data)                    Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendSticker(array $data)                     Use this method to send .webp stickers. On success, the sent Message is returned.
 * @method static ServerResponse sendVideo(array $data)                       Use this method to send video files, Telegram clients support mp4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendAnimation(array $data)                   Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendVoice(array $data)                       Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .ogg file encoded with OPUS (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
 * @method static ServerResponse sendVideoNote(array $data)                   Use this method to send video messages. On success, the sent Message is returned.
 * @method static ServerResponse sendMediaGroup(array $data)                  Use this method to send a group of photos or videos as an album. On success, an array of the sent Messages is returned.
 * @method static ServerResponse sendLocation(array $data)                    Use this method to send point on the map. On success, the sent Message is returned.
 * @method static ServerResponse editMessageLiveLocation(array $data)         Use this method to edit live location messages sent by the bot or via the bot (for inline bots). A location can be edited until its live_period expires or editing is explicitly disabled by a call to stopMessageLiveLocation. On success, if the edited message was sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static ServerResponse stopMessageLiveLocation(array $data)         Use this method to stop updating a live location message sent by the bot or via the bot (for inline bots) before live_period expires. On success, if the message was sent by the bot, the sent Message is returned, otherwise True is returned.
 * @method static ServerResponse sendVenue(array $data)                       Use this method to send information about a venue. On success, the sent Message is returned.
 * @method static ServerResponse sendContact(array $data)                     Use this method to send phone contacts. On success, the sent Message is returned.
 * @method static ServerResponse sendPoll(array $data)                        Use this method to send a native poll. A native poll can't be sent to a private chat. On success, the sent Message is returned.
 * @method static ServerResponse sendDice(array $data)                        Use this method to send a dice, which will have a random value from 1 to 6. On success, the sent Message is returned.
 * @method static ServerResponse sendChatAction(array $data)                  Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success.
 * @method static ServerResponse getUserProfilePhotos(array $data)            Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
 * @method static ServerResponse getFile(array $data)                         Use this method to get basic info about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
 * @method static ServerResponse banChatMember(array $data)                   Use this method to kick a user from a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the group on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse unbanChatMember(array $data)                 Use this method to unban a previously kicked user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. Returns True on success.
 * @method static ServerResponse restrictChatMember(array $data)              Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate admin rights. Pass True for all permissions to lift restrictions from a user. Returns True on success.
 * @method static ServerResponse promoteChatMember(array $data)               Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Pass False for all boolean parameters to demote a user. Returns True on success.
 * @method static ServerResponse setChatAdministratorCustomTitle(array $data) Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns True on success.
 * @method static ServerResponse banChatSenderChat(array $data)               Use this method to ban a channel chat in a supergroup or a channel. Until the chat is unbanned, the owner of the banned chat won't be able to send messages on behalf of any of their channels. The bot must be an administrator in the supergroup or channel for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static ServerResponse unbanChatSenderChat(array $data)             Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns True on success.
 * @method static ServerResponse setChatPermissions(array $data)              Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the can_restrict_members admin rights. Returns True on success.
 * @method static ServerResponse exportChatInviteLink(array $data)            Use this method to generate a new invite link for a chat. Any previously generated link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns the new invite link as String on success.
 * @method static ServerResponse createChatInviteLink(array $data)            Use this method to create an additional invite link for a chat. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. The link can be revoked using the method revokeChatInviteLink. Returns the new invite link as ChatInviteLink object.
 * @method static ServerResponse editChatInviteLink(array $data)              Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns the edited invite link as a ChatInviteLink object.
 * @method static ServerResponse revokeChatInviteLink(array $data)            Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns the revoked invite link as ChatInviteLink object.
 * @method static ServerResponse approveChatJoinRequest(array $data)          Use this method to approve a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static ServerResponse declineChatJoinRequest(array $data)          Use this method to decline a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
 * @method static ServerResponse setChatPhoto(array $data)                    Use this method to set a new profile photo for the chat. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse deleteChatPhoto(array $data)                 Use this method to delete a chat photo. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse setChatTitle(array $data)                    Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse setChatDescription(array $data)              Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
 * @method static ServerResponse pinChatMessage(array $data)                  Use this method to pin a message in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the ‘can_pin_messages’ admin right in the supergroup or ‘can_edit_messages’ admin right in the channel. Returns True on success.
 * @method static ServerResponse unpinChatMessage(array $data)                Use this method to unpin a message in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the ‘can_pin_messages’ admin right in the supergroup or ‘can_edit_messages’ admin right in the channel. Returns True on success.
 * @method static ServerResponse unpinAllChatMessages(array $data)            Use this method to clear the list of pinned messages in a chat. If the chat is not a private chat, the bot must be an administrator in the chat for this to work and must have the 'can_pin_messages' admin right in a supergroup or 'can_edit_messages' admin right in a channel. Returns True on success.
 * @method static ServerResponse leaveChat(array $data)                       Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
 * @method static ServerResponse getChat(array $data)                         Use this method to get up to date information about the chat (current name of the user for one-on-one conversations, current username of a user, group or channel, etc.). Returns a Chat object on success.
 * @method static ServerResponse getChatAdministrators(array $data)           Use this method to get a list of administrators in a chat. On success, returns an Array of ChatMember objects that contains information about all chat administrators except other bots. If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.
 * @method static ServerResponse getChatMemberCount(array $data)              Use this method to get the number of members in a chat. Returns Int on success.
 * @method static ServerResponse getChatMember(array $data)                   Use this method to get information about a member of a chat. Returns a ChatMember object on success.
 * @method static ServerResponse setChatStickerSet(array $data)               Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static ServerResponse deleteChatStickerSet(array $data)            Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
 * @method static ServerResponse getForumTopicIconStickers(array $data)       Use this method to get custom emoji stickers, which can be used as a forum topic icon by any user. Requires no parameters. Returns an Array of Sticker objects
 * @method static ServerResponse createForumTopic(array $data)                Use this method to create a topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns information about the created topic as a ForumTopic object.
 * @method static ServerResponse editForumTopic(array $data)                  Use this method to edit name and icon of a topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static ServerResponse closeForumTopic(array $data)                 Use this method to close an open topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static ServerResponse reopenForumTopic(array $data)                Use this method to reopen a closed topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
 * @method static ServerResponse deleteForumTopic(array $data)                Use this method to delete a forum topic along with all its messages in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_delete_messages administrator rights. Returns True on success.
 * @method static ServerResponse unpinAllForumTopicMessages(array $data)      Use this method to clear the list of pinned messages in a forum topic. The bot must be an administrator in the chat for this to work and must have the can_pin_messages administrator right in the supergroup. Returns True on success.
 * @method static ServerResponse answerCallbackQuery(array $data)             Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
 * @method static ServerResponse answerInlineQuery(array $data)               Use this method to send answers to an inline query. On success, True is returned.
 * @method static ServerResponse setMyCommands(array $data)                   Use this method to change the list of the bot's commands. Returns True on success.
 * @method static ServerResponse deleteMyCommands(array $data)                Use this method to delete the list of the bot's commands for the given scope and user language. After deletion, higher level commands will be shown to affected users. Returns True on success.
 * @method static ServerResponse getMyCommands(array $data)                   Use this method to get the current list of the bot's commands. Requires no parameters. Returns Array of BotCommand on success.
 * @method static ServerResponse setChatMenuButton(array $data)               Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
 * @method static ServerResponse getChatMenuButton(array $data)               Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
 * @method static ServerResponse setMyDefaultAdministratorRights(array $data) Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are are free to modify the list before adding the bot. Returns True on success.
 * @method static ServerResponse getMyDefaultAdministratorRights(array $data) Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
 * @method static ServerResponse editMessageText(array $data)                 Use this method to edit text and game messages sent by the bot or via the bot (for inline bots). On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static ServerResponse editMessageCaption(array $data)              Use this method to edit captions of messages sent by the bot or via the bot (for inline bots). On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static ServerResponse editMessageMedia(array $data)                Use this method to edit audio, document, photo, or video messages. On success, if the edited message was sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static ServerResponse editMessageReplyMarkup(array $data)          Use this method to edit only the reply markup of messages sent by the bot or via the bot (for inline bots). On success, if edited message is sent by the bot, the edited Message is returned, otherwise True is returned.
 * @method static ServerResponse stopPoll(array $data)                        Use this method to stop a poll which was sent by the bot. On success, the stopped Poll with the final results is returned.
 * @method static ServerResponse deleteMessage(array $data)                   Use this method to delete a message, including service messages, with certain limitations. Returns True on success.
 * @method static ServerResponse getStickerSet(array $data)                   Use this method to get a sticker set. On success, a StickerSet object is returned.
 * @method static ServerResponse getCustomEmojiStickers(array $data)          Use this method to get information about custom emoji stickers by their identifiers. Returns an Array of Sticker objects.
 * @method static ServerResponse uploadStickerFile(array $data)               Use this method to upload a .png file with a sticker for later use in createNewStickerSet and addStickerToSet methods (can be used multiple times). Returns the uploaded File on success.
 * @method static ServerResponse createNewStickerSet(array $data)             Use this method to create new sticker set owned by a user. The bot will be able to edit the created sticker set. Returns True on success.
 * @method static ServerResponse addStickerToSet(array $data)                 Use this method to add a new sticker to a set created by the bot. Returns True on success.
 * @method static ServerResponse setStickerPositionInSet(array $data)         Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
 * @method static ServerResponse deleteStickerFromSet(array $data)            Use this method to delete a sticker from a set created by the bot. Returns True on success.
 * @method static ServerResponse setStickerSetThumb(array $data)              Use this method to set the thumbnail of a sticker set. Animated thumbnails can be set for animated sticker sets only. Returns True on success.
 * @method static ServerResponse answerWebAppQuery(array $data)               Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
 * @method static ServerResponse sendInvoice(array $data)                     Use this method to send invoices. On success, the sent Message is returned.
 * @method static ServerResponse createInvoiceLink(array $data)               Use this method to create a link for an invoice. Returns the created invoice link as String on success.
 * @method static ServerResponse answerShippingQuery(array $data)             If you sent an invoice requesting a shipping address and the parameter is_flexible was specified, the Bot API will send an Update with a shipping_query field to the bot. Use this method to reply to shipping queries. On success, True is returned.
 * @method static ServerResponse answerPreCheckoutQuery(array $data)          Once the user has confirmed their payment and shipping details, the Bot API sends the final confirmation in the form of an Update with the field pre_checkout_query. Use this method to respond to such pre-checkout queries. On success, True is returned.
 * @method static ServerResponse setPassportDataErrors(array $data)           Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success. Use this if the data submitted by the user doesn't satisfy the standards your service requires for any reason. For example, if a birthday date seems invalid, a submitted document is blurry, a scan shows evidence of tampering, etc. Supply some details in the error message to make sure the user knows how to correct the issues.
 * @method static ServerResponse sendGame(array $data)                        Use this method to send a game. On success, the sent Message is returned.
 * @method static ServerResponse setGameScore(array $data)                    Use this method to set the score of the specified user in a game. On success, if the message was sent by the bot, returns the edited Message, otherwise returns True. Returns an error, if the new score is not greater than the user's current score in the chat and force is False.
 * @method static ServerResponse getGameHighScores(array $data)               Use this method to get data for high score tables. Will return the score of the specified user and several of his neighbors in a game. On success, returns an Array of GameHighScore objects.
 */
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
     * URI of the Telegram API for downloading files (relative to $api_base_url or absolute)
     *
     * @var string
     */
    private static $api_base_download_uri = '/file/bot{API_KEY}';

    /**
     * Guzzle Client object
     *
     * @var ClientInterface
     */
    private static $client;

    /**
     * Request limiter
     *
     * @var bool
     */
    private static $limiter_enabled;

    /**
     * Request limiter's interval between checks
     *
     * @var float
     */
    private static $limiter_interval;

    /**
     * The current action that is being executed
     *
     * @var string
     */
    private static $current_action = '';

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
        'logOut',
        'close',
        'sendMessage',
        'forwardMessage',
        'copyMessage',
        'sendPhoto',
        'sendAudio',
        'sendDocument',
        'sendSticker',
        'sendVideo',
        'sendAnimation',
        'sendVoice',
        'sendVideoNote',
        'sendMediaGroup',
        'sendLocation',
        'editMessageLiveLocation',
        'stopMessageLiveLocation',
        'sendVenue',
        'sendContact',
        'sendPoll',
        'sendDice',
        'sendChatAction',
        'getUserProfilePhotos',
        'getFile',
        'banChatMember',
        'unbanChatMember',
        'restrictChatMember',
        'promoteChatMember',
        'setChatAdministratorCustomTitle',
        'banChatSenderChat',
        'unbanChatSenderChat',
        'setChatPermissions',
        'exportChatInviteLink',
        'createChatInviteLink',
        'editChatInviteLink',
        'revokeChatInviteLink',
        'approveChatJoinRequest',
        'declineChatJoinRequest',
        'setChatPhoto',
        'deleteChatPhoto',
        'setChatTitle',
        'setChatDescription',
        'pinChatMessage',
        'unpinChatMessage',
        'unpinAllChatMessages',
        'leaveChat',
        'getChat',
        'getChatAdministrators',
        'getChatMemberCount',
        'getChatMember',
        'setChatStickerSet',
        'deleteChatStickerSet',
        'getForumTopicIconStickers',
        'createForumTopic',
        'editForumTopic',
        'closeForumTopic',
        'reopenForumTopic',
        'deleteForumTopic',
        'unpinAllForumTopicMessages',
        'answerCallbackQuery',
        'answerInlineQuery',
        'setMyCommands',
        'deleteMyCommands',
        'getMyCommands',
        'setChatMenuButton',
        'getChatMenuButton',
        'setMyDefaultAdministratorRights',
        'getMyDefaultAdministratorRights',
        'editMessageText',
        'editMessageCaption',
        'editMessageMedia',
        'editMessageReplyMarkup',
        'stopPoll',
        'deleteMessage',
        'getStickerSet',
        'getCustomEmojiStickers',
        'uploadStickerFile',
        'createNewStickerSet',
        'addStickerToSet',
        'setStickerPositionInSet',
        'deleteStickerFromSet',
        'setStickerSetThumb',
        'answerWebAppQuery',
        'sendInvoice',
        'createInvoiceLink',
        'answerShippingQuery',
        'answerPreCheckoutQuery',
        'setPassportDataErrors',
        'sendGame',
        'setGameScore',
        'getGameHighScores',
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
        'logOut',
        'close',
        'deleteMyCommands',
        'getMyCommands',
        'setChatMenuButton',
        'getChatMenuButton',
        'setMyDefaultAdministratorRights',
        'getMyDefaultAdministratorRights',
    ];

    /**
     * Available fields for InputFile helper
     *
     * This is basically the list of all fields that allow InputFile objects
     * for which input can be simplified by providing local path directly  as string.
     *
     * @var array
     */
    private static $input_file_fields = [
        'setWebhook'          => ['certificate'],
        'sendPhoto'           => ['photo'],
        'sendAudio'           => ['audio', 'thumb'],
        'sendDocument'        => ['document', 'thumb'],
        'sendVideo'           => ['video', 'thumb'],
        'sendAnimation'       => ['animation', 'thumb'],
        'sendVoice'           => ['voice', 'thumb'],
        'sendVideoNote'       => ['video_note', 'thumb'],
        'setChatPhoto'        => ['photo'],
        'sendSticker'         => ['sticker'],
        'uploadStickerFile'   => ['png_sticker'],
        'createNewStickerSet' => ['png_sticker', 'tgs_sticker', 'webm_sticker'],
        'addStickerToSet'     => ['png_sticker', 'tgs_sticker', 'webm_sticker'],
        'setStickerSetThumb'  => ['thumb'],
    ];

    /**
     * Initialize
     *
     * @param Telegram $telegram
     */
    public static function initialize(Telegram $telegram): void
    {
        self::$telegram = $telegram;
        self::setClient(self::$client ?: new Client(['base_uri' => self::$api_base_uri]));
    }

    /**
     * Set a custom Guzzle HTTP Client object
     *
     * @param ClientInterface $client
     */
    public static function setClient(ClientInterface $client): void
    {
        self::$client = $client;
    }

    /**
     * Set a custom Bot API URL
     *
     * @param string $api_base_uri
     * @param string $api_base_download_uri
     */
    public static function setCustomBotApiUri(string $api_base_uri, string $api_base_download_uri = ''): void
    {
        self::$api_base_uri = $api_base_uri;
        if ($api_base_download_uri !== '') {
            self::$api_base_download_uri = $api_base_download_uri;
        }
    }

    /**
     * Get input from custom input or stdin and return it
     *
     * @return string
     */
    public static function getInput(): string
    {
        // First check if a custom input has been set, else get the PHP input.
        return self::$telegram->getCustomInput()
            ?: file_get_contents('php://input');
    }

    /**
     * Generate general fake server response
     *
     * @param array $data Data to add to fake response
     *
     * @return array Fake response data
     */
    public static function generateGeneralFakeServerResponse(array $data = []): array
    {
        //PARAM BINDED IN PHPUNIT TEST FOR TestServerResponse.php
        //Maybe this is not the best possible implementation

        //No value set in $data ie testing setWebhook
        //Provided $data['chat_id'] ie testing sendMessage

        $fake_response = ['ok' => true]; // :)

        if ($data === []) {
            $fake_response['result'] = true;
        }

        //some data to initialize the class method SendMessage
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
     * @throws TelegramException
     */
    private static function setUpRequestParams(array $data): array
    {
        $has_resource = false;
        $multipart    = [];

        foreach ($data as $key => &$item) {
            if ($key === 'media') {
                // Magical media input helper.
                $item = self::mediaInputHelper($item, $has_resource, $multipart);
            } elseif (array_key_exists(self::$current_action, self::$input_file_fields) && in_array($key, self::$input_file_fields[self::$current_action], true)) {
                // Allow absolute paths to local files.
                if (is_string($item) && file_exists($item)) {
                    $item = new Stream(self::encodeFile($item));
                }
            } elseif (is_array($item) || is_object($item)) {
                // Convert any nested arrays or objects into JSON strings.
                $item = json_encode($item);
            }

            // Reformat data array in multipart way if it contains a resource
            $has_resource = $has_resource || is_resource($item) || $item instanceof Stream;
            $multipart[]  = ['name' => $key, 'contents' => $item];
        }
        unset($item);

        if ($has_resource) {
            return ['multipart' => $multipart];
        }

        return ['form_params' => $data];
    }

    /**
     * Magical input media helper to simplify passing media.
     *
     * This allows the following:
     * Request::editMessageMedia([
     *     ...
     *     'media' => new InputMediaPhoto([
     *         'caption' => 'Caption!',
     *         'media'   => Request::encodeFile($local_photo),
     *     ]),
     * ]);
     * and
     * Request::sendMediaGroup([
     *     'media'   => [
     *         new InputMediaPhoto(['media' => Request::encodeFile($local_photo_1)]),
     *         new InputMediaPhoto(['media' => Request::encodeFile($local_photo_2)]),
     *         new InputMediaVideo(['media' => Request::encodeFile($local_video_1)]),
     *     ],
     * ]);
     * and even
     * Request::sendMediaGroup([
     *     'media'   => [
     *         new InputMediaPhoto(['media' => $local_photo_1]),
     *         new InputMediaPhoto(['media' => $local_photo_2]),
     *         new InputMediaVideo(['media' => $local_video_1]),
     *     ],
     * ]);
     *
     * @param mixed $item
     * @param bool  $has_resource
     * @param array $multipart
     *
     * @return mixed
     * @throws TelegramException
     */
    private static function mediaInputHelper($item, bool &$has_resource, array &$multipart)
    {
        $was_array = is_array($item);
        $was_array || $item = [$item];

        /** @var InputMedia|null $media_item */
        foreach ($item as $media_item) {
            if (!($media_item instanceof InputMedia)) {
                continue;
            }

            // Make a list of all possible media that can be handled by the helper.
            $possible_medias = array_filter([
                'media' => $media_item->getMedia(),
                'thumb' => $media_item->getThumb(),
            ]);

            foreach ($possible_medias as $type => $media) {
                // Allow absolute paths to local files.
                if (is_string($media) && strpos($media, 'attach://') !== 0 && file_exists($media)) {
                    $media = new Stream(self::encodeFile($media));
                }

                if (is_resource($media) || $media instanceof Stream) {
                    $has_resource = true;
                    $unique_key   = uniqid($type . '_', false);
                    $multipart[]  = ['name' => $unique_key, 'contents' => $media];

                    // We're literally overwriting the passed media type data!
                    $media_item->$type           = 'attach://' . $unique_key;
                    $media_item->raw_data[$type] = 'attach://' . $unique_key;
                }
            }
        }

        $was_array || $item = reset($item);

        return json_encode($item);
    }

    /**
     * Get the current action that's being executed
     *
     * @return string
     */
    public static function getCurrentAction(): string
    {
        return self::$current_action;
    }

    /**
     * Execute HTTP Request
     *
     * @param string $action Action to execute
     * @param array  $data   Data to attach to the execution
     *
     * @return string Result of the HTTP Request
     * @throws TelegramException
     */
    public static function execute(string $action, array $data = []): string
    {
        $request_params          = self::setUpRequestParams($data);
        $request_params['debug'] = TelegramLog::getDebugLogTempStream();

        try {
            $response = self::$client->post(
                '/bot' . self::$telegram->getApiKey() . '/' . $action,
                $request_params
            );
            $result   = (string) $response->getBody();
        } catch (RequestException $e) {
            $response = null;
            $result   = $e->getResponse() ? (string) $e->getResponse()->getBody() : '';
        }

        //Logging verbose debug output
        if (TelegramLog::$always_log_request_and_response || $response === null) {
            TelegramLog::debug('Request data:' . PHP_EOL . print_r($data, true));
            TelegramLog::debug('Response data:' . PHP_EOL . $result);
            TelegramLog::endDebugLogTempStream('Verbose HTTP Request output:' . PHP_EOL . '%s' . PHP_EOL);
        }

        return $result;
    }

    /**
     * Download file
     *
     * @param File $file
     *
     * @return bool
     * @throws TelegramException
     */
    public static function downloadFile(File $file): bool
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
            $base_download_uri = str_replace('{API_KEY}', self::$telegram->getApiKey(), self::$api_base_download_uri);
            self::$client->get(
                "{$base_download_uri}/{$tg_file_path}",
                ['debug' => $debug_handle, 'sink' => $file_path]
            );

            return filesize($file_path) > 0;
        } catch (Throwable $e) {
            return false;
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
     * @throws TelegramException
     */
    public static function encodeFile(string $file)
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
     * @return ServerResponse
     * @throws TelegramException
     */
    public static function send(string $action, array $data = []): ServerResponse
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

        // Remember which action is currently being executed.
        self::$current_action = $action;

        $raw_response = self::execute($action, $data);
        $response     = json_decode($raw_response, true);

        if (null === $response) {
            TelegramLog::debug($raw_response);
            throw new TelegramException('Telegram returned an invalid response!');
        }

        $response = new ServerResponse($response, $bot_username);

        if (!$response->isOk() && $response->getErrorCode() === 401 && $response->getDescription() === 'Unauthorized') {
            throw new InvalidBotTokenException();
        }

        // Special case for sent polls, which need to be saved specially.
        // @todo Take into account if DB gets extracted into separate module.
        if ($response->isOk() && ($message = $response->getResult()) && ($message instanceof Message) && $poll = $message->getPoll()) {
            DB::insertPollRequest($poll);
        }

        // Reset current action after completion.
        self::$current_action = '';

        return $response;
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
    protected static function addDummyParamIfNecessary(string $action, array &$data): void
    {
        if (empty($data) && in_array($action, self::$actions_need_dummy_param, true)) {
            // Can be anything, using a single letter to minimise request size.
            $data = ['d'];
        }
    }

    /**
     * Make sure the data isn't empty, else throw an exception
     *
     * @param array $data
     *
     * @throws TelegramException
     */
    private static function ensureNonEmptyData(array $data): void
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
     * @throws TelegramException
     */
    private static function ensureValidAction(string $action): void
    {
        if (!in_array($action, self::$actions, true)) {
            throw new TelegramException('The action "' . $action . '" doesn\'t exist!');
        }
    }

    /**
     * Use this method to send text messages. On success, the last sent Message is returned
     *
     * All message responses are saved in `$extras['responses']`.
     * Custom encoding can be defined in `$extras['encoding']` (default: `mb_internal_encoding()`)
     * Custom splitting can be defined in `$extras['split']` (default: 4096)
     *     `$extras['split'] = null;` // force to not split message at all!
     *     `$extras['split'] = 200;`  // split message into 200 character chunks
     *
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @todo Splitting formatted text may break the message.
     *
     * @param array      $data
     * @param array|null $extras
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public static function sendMessage(array $data, ?array &$extras = []): ServerResponse
    {
        $extras = array_merge([
            'split'    => 4096,
            'encoding' => mb_internal_encoding(),
        ], (array) $extras);

        $text       = $data['text'];
        $encoding   = $extras['encoding'];
        $max_length = $extras['split'] ?: mb_strlen($text, $encoding);

        $responses = [];

        do {
            // Chop off and send the first message.
            $data['text'] = mb_substr($text, 0, $max_length, $encoding);
            $responses[]  = self::send('sendMessage', $data);

            // Prepare the next message.
            $text = mb_substr($text, $max_length, null, $encoding);
        } while ($text !== '');

        // Add all response objects to referenced variable.
        $extras['responses'] = $responses;

        return end($responses);
    }

    /**
     * Any statically called method should be relayed to the `send` method.
     *
     * @param string $action
     * @param array  $data
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public static function __callStatic(string $action, array $data): ServerResponse
    {
        // Only argument should be the data array, ignore any others.
        return static::send($action, reset($data) ?: []);
    }

    /**
     * Return an empty Server Response
     *
     * No request is sent to Telegram.
     * This function is used in commands that don't need to fire a message after execution
     *
     * @return ServerResponse
     */
    public static function emptyResponse(): ServerResponse
    {
        return new ServerResponse(['ok' => true, 'result' => true]);
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
        string $callback_function,
        array $data,
        array $select_chats_params
    ): array {
        self::ensureValidAction($callback_function);

        $chats = DB::selectChats($select_chats_params);

        $results = [];
        if (is_array($chats)) {
            foreach ($chats as $row) {
                $data['chat_id'] = $row['chat_id'];
                $results[]       = self::send($callback_function, $data);
            }
        }

        return $results;
    }

    /**
     * Enable request limiter
     *
     * @param bool  $enable
     * @param array $options
     *
     * @throws TelegramException
     */
    public static function setLimiter(bool $enable = true, array $options = []): void
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
            self::$limiter_enabled  = $enable;
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
     * @throws TelegramException
     */
    private static function limitTelegramRequests(string $action, array $data = []): void
    {
        if (self::$limiter_enabled) {
            $limited_methods = [
                'sendMessage',
                'forwardMessage',
                'copyMessage',
                'sendPhoto',
                'sendAudio',
                'sendDocument',
                'sendSticker',
                'sendVideo',
                'sendAnimation',
                'sendVoice',
                'sendVideoNote',
                'sendMediaGroup',
                'sendLocation',
                'editMessageLiveLocation',
                'stopMessageLiveLocation',
                'sendVenue',
                'sendContact',
                'sendPoll',
                'sendDice',
                'sendInvoice',
                'sendGame',
                'setGameScore',
                'setMyCommands',
                'deleteMyCommands',
                'editMessageText',
                'editMessageCaption',
                'editMessageMedia',
                'editMessageReplyMarkup',
                'stopPoll',
                'setChatTitle',
                'setChatDescription',
                'setChatStickerSet',
                'deleteChatStickerSet',
                'setPassportDataErrors',
            ];

            $chat_id           = $data['chat_id'] ?? null;
            $inline_message_id = $data['inline_message_id'] ?? null;

            if (($chat_id || $inline_message_id) && in_array($action, $limited_methods, true)) {
                $timeout = 60;

                while (true) {
                    if ($timeout <= 0) {
                        throw new TelegramException('Timed out while waiting for a request spot!');
                    }

                    if (!($requests = DB::getTelegramRequestCount($chat_id, $inline_message_id))) {
                        break;
                    }

                    // Make sure we're handling integers here.
                    $requests = array_map('intval', $requests);

                    $chat_per_second   = ($requests['LIMIT_PER_SEC'] === 0);    // No more than one message per second inside a particular chat
                    $global_per_second = ($requests['LIMIT_PER_SEC_ALL'] < 30); // No more than 30 messages per second to different chats
                    $groups_per_minute = (((is_numeric($chat_id) && $chat_id > 0) || $inline_message_id !== null) || ((!is_numeric($chat_id) || $chat_id < 0) && $requests['LIMIT_PER_MINUTE'] < 20));    // No more than 20 messages per minute in groups and channels

                    if ($chat_per_second && $global_per_second && $groups_per_minute) {
                        break;
                    }

                    $timeout--;
                    usleep((int) (self::$limiter_interval * 1000000));
                }

                DB::insertTelegramRequest($action, $data);
            }
        }
    }

    /**
     * Use this method to kick a user from a group, a supergroup or a channel. In the case of supergroups and channels, the user will not be able to return to the group on their own using invite links, etc., unless unbanned first. The bot must be an administrator in the chat for this to work and must have the appropriate admin rights. Returns True on success.
     *
     * @deprecated
     * @see Request::banChatMember()
     *
     * @param array $data
     *
     * @return ServerResponse
     */
    public static function kickChatMember(array $data = []): ServerResponse
    {
        return static::banChatMember($data);
    }
}
