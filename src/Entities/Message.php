<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Entities\Games\Game;
use Longman\TelegramBot\Entities\Payments\Invoice;
use Longman\TelegramBot\Entities\Payments\SuccessfulPayment;
use Longman\TelegramBot\Entities\TelegramPassport\PassportData;

/**
 * Class Message
 *
 * @link https://core.telegram.org/bots/api#message
 *
 * @method int               getMessageId()             Unique message identifier
 * @method User              getFrom()                  Optional. Sender, can be empty for messages sent to channels
 * @method int               getDate()                  Date the message was sent in Unix time
 * @method Chat              getChat()                  Conversation the message belongs to
 * @method User              getForwardFrom()           Optional. For forwarded messages, sender of the original message
 * @method Chat              getForwardFromChat()       Optional. For messages forwarded from a channel, information about the original channel
 * @method int               getForwardFromMessageId()  Optional. For forwarded channel posts, identifier of the original message in the channel
 * @method string            getForwardSignature()      Optional. For messages forwarded from channels, signature of the post author if present
 * @method int               getForwardDate()           Optional. For forwarded messages, date the original message was sent in Unix time
 * @method Message           getReplyToMessage()        Optional. For replies, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
 * @method int               getEditDate()              Optional. Date the message was last edited in Unix time
 * @method string            getMediaGroupId()          Optional. The unique identifier of a media message group this message belongs to
 * @method string            getAuthorSignature()       Optional. Signature of the post author for messages in channels
 * @method Audio             getAudio()                 Optional. Message is an audio file, information about the file
 * @method Document          getDocument()              Optional. Message is a general file, information about the file
 * @method Animation         getAnimation()             Optional. Message is an animation, information about the animation. For backward compatibility, when this field is set, the document field will also be set
 * @method Game              getGame()                  Optional. Message is a game, information about the game.
 * @method Sticker           getSticker()               Optional. Message is a sticker, information about the sticker
 * @method Video             getVideo()                 Optional. Message is a video, information about the video
 * @method Voice             getVoice()                 Optional. Message is a voice message, information about the file
 * @method VideoNote         getVideoNote()             Optional. Message is a video note message, information about the video
 * @method string            getCaption()               Optional. Caption for the document, photo or video, 0-200 characters
 * @method Contact           getContact()               Optional. Message is a shared contact, information about the contact
 * @method Location          getLocation()              Optional. Message is a shared location, information about the location
 * @method Venue             getVenue()                 Optional. Message is a venue, information about the venue
 * @method User              getLeftChatMember()        Optional. A member was removed from the group, information about them (this member may be the bot itself)
 * @method string            getNewChatTitle()          Optional. A chat title was changed to this value
 * @method bool              getDeleteChatPhoto()       Optional. Service message: the chat photo was deleted
 * @method bool              getGroupChatCreated()      Optional. Service message: the group has been created
 * @method bool              getSupergroupChatCreated() Optional. Service message: the supergroup has been created. This field can't be received in a message coming through updates, because bot can’t be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
 * @method bool              getChannelChatCreated()    Optional. Service message: the channel has been created. This field can't be received in a message coming through updates, because bot can’t be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
 * @method int               getMigrateToChatId()       Optional. The group has been migrated to a supergroup with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method int               getMigrateFromChatId()     Optional. The supergroup has been migrated from a group with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method Message           getPinnedMessage()         Optional. Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it is itself a reply.
 * @method Invoice           getInvoice()               Optional. Message is an invoice for a payment, information about the invoice.
 * @method SuccessfulPayment getSuccessfulPayment()     Optional. Message is a service message about a successful payment, information about the payment.
 * @method string            getConnectedWebsite()      Optional. The domain name of the website on which the user has logged in.
 * @method PassportData      getPassportData()          Optional. Telegram Passport data
 */
class Message extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'from'               => User::class,
            'chat'               => Chat::class,
            'forward_from'       => User::class,
            'forward_from_chat'  => Chat::class,
            'reply_to_message'   => ReplyToMessage::class,
            'entities'           => MessageEntity::class,
            'caption_entities'   => MessageEntity::class,
            'audio'              => Audio::class,
            'document'           => Document::class,
            'animation'          => Animation::class,
            'game'               => Game::class,
            'photo'              => PhotoSize::class,
            'sticker'            => Sticker::class,
            'video'              => Video::class,
            'voice'              => Voice::class,
            'video_note'         => VideoNote::class,
            'contact'            => Contact::class,
            'location'           => Location::class,
            'venue'              => Venue::class,
            'new_chat_members'   => User::class,
            'left_chat_member'   => User::class,
            'new_chat_photo'     => PhotoSize::class,
            'pinned_message'     => Message::class,
            'invoice'            => Invoice::class,
            'successful_payment' => SuccessfulPayment::class,
            'passport_data'      => PassportData::class,
        ];
    }

    /**
     * Message constructor
     *
     * @param array  $data
     * @param string $bot_username
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data, $bot_username = '')
    {
        parent::__construct($data, $bot_username);
    }

    /**
     * Optional. Message is a photo, available sizes of the photo
     *
     * This method overrides the default getPhoto method
     * and returns a nice array of PhotoSize objects.
     *
     * @return null|PhotoSize[]
     */
    public function getPhoto()
    {
        $pretty_array = $this->makePrettyObjectArray(PhotoSize::class, 'photo');

        return empty($pretty_array) ? null : $pretty_array;
    }

    /**
     * Optional. A chat photo was changed to this value
     *
     * This method overrides the default getNewChatPhoto method
     * and returns a nice array of PhotoSize objects.
     *
     * @return null|PhotoSize[]
     */
    public function getNewChatPhoto()
    {
        $pretty_array = $this->makePrettyObjectArray(PhotoSize::class, 'new_chat_photo');

        return empty($pretty_array) ? null : $pretty_array;
    }

    /**
     * Optional. A new member(s) was added to the group, information about them (one of this members may be the bot itself)
     *
     * This method overrides the default getNewChatMembers method
     * and returns a nice array of User objects.
     *
     * @return null|User[]
     */
    public function getNewChatMembers()
    {
        $pretty_array = $this->makePrettyObjectArray(User::class, 'new_chat_members');

        return empty($pretty_array) ? null : $pretty_array;
    }

    /**
     * Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
     *
     * This method overrides the default getEntities method
     * and returns a nice array of MessageEntity objects.
     *
     * @return null|MessageEntity[]
     */
    public function getEntities()
    {
        $pretty_array = $this->makePrettyObjectArray(MessageEntity::class, 'entities');

        return empty($pretty_array) ? null : $pretty_array;
    }

    /**
     * Optional. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
     *
     * This method overrides the default getCaptionEntities method
     * and returns a nice array of MessageEntity objects.
     *
     * @return null|MessageEntity[]
     */
    public function getCaptionEntities()
    {
        $pretty_array = $this->makePrettyObjectArray(MessageEntity::class, 'caption_entities');

        return empty($pretty_array) ? null : $pretty_array;
    }

    /**
     * return the entire command like /echo or /echo@bot1 if specified
     *
     * @return string|null
     */
    public function getFullCommand()
    {
        $text = $this->getProperty('text');
        if (strpos($text, '/') !== 0) {
            return null;
        }

        $no_EOL   = strtok($text, PHP_EOL);
        $no_space = strtok($text, ' ');

        //try to understand which separator \n or space divide /command from text
        return strlen($no_space) < strlen($no_EOL) ? $no_space : $no_EOL;
    }

    /**
     * Get command
     *
     * @return string|null
     */
    public function getCommand()
    {
        if ($command = $this->getProperty('command')) {
            return $command;
        }

        $full_command = $this->getFullCommand();
        if (strpos($full_command, '/') !== 0) {
            return null;
        }
        $full_command = substr($full_command, 1);

        //check if command is followed by bot username
        $split_cmd = explode('@', $full_command);
        if (!isset($split_cmd[1])) {
            //command is not followed by name
            return $full_command;
        }

        if (strtolower($split_cmd[1]) === strtolower($this->getBotUsername())) {
            //command is addressed to me
            return $split_cmd[0];
        }

        return null;
    }

    /**
     * For text messages, the actual UTF-8 text of the message, 0-4096 characters.
     *
     * @param bool $without_cmd
     *
     * @return string
     */
    public function getText($without_cmd = false)
    {
        $text = $this->getProperty('text');

        if ($without_cmd && $command = $this->getFullCommand()) {
            if (strlen($command) + 1 < strlen($text)) {
                return substr($text, strlen($command) + 1);
            }

            return '';
        }

        return $text;
    }

    /**
     * Bot added in chat
     *
     * @return bool
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function botAddedInChat()
    {
        foreach ($this->getNewChatMembers() as $member) {
            if ($member instanceof User && $member->getUsername() === $this->getBotUsername()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect type based on properties.
     *
     * @return string
     */
    public function getType()
    {
        $types = [
            'text',
            'audio',
            'document',
            'animation',
            'game',
            'photo',
            'sticker',
            'video',
            'voice',
            'video_note',
            'contact',
            'location',
            'venue',
            'new_chat_members',
            'left_chat_member',
            'new_chat_title',
            'new_chat_photo',
            'delete_chat_photo',
            'group_chat_created',
            'supergroup_chat_created',
            'channel_chat_created',
            'migrate_to_chat_id',
            'migrate_from_chat_id',
            'pinned_message',
            'invoice',
            'successful_payment',
            'passport_data',
        ];

        $is_command = strlen($this->getCommand()) > 0;
        foreach ($types as $type) {
            if ($this->getProperty($type) !== null) {
                if ($is_command && $type === 'text') {
                    return 'command';
                }

                return $type;
            }
        }

        return 'message';
    }
}
