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
use Longman\TelegramBot\Entities\Topics\ForumTopicClosed;
use Longman\TelegramBot\Entities\Topics\ForumTopicCreated;
use Longman\TelegramBot\Entities\Topics\ForumTopicReopened;

/**
 * Class Message
 *
 * Represents a message
 *
 * @link https://core.telegram.org/bots/api#message
 *
 * @method int                                    getMessageId()                              Unique message identifier
 * @method int                                    getMessageThreadId()                        Optional. Unique identifier of a message thread to which the message belongs; for supergroups only
 * @method User                                   getFrom()                                   Optional. Sender, can be empty for messages sent to channels
 * @method Chat                                   getSenderChat()                             Optional. Sender of the message, sent on behalf of a chat. The channel itself for channel messages. The supergroup itself for messages from anonymous group administrators. The linked channel for messages automatically forwarded to the discussion group
 * @method int                                    getDate()                                   Date the message was sent in Unix time
 * @method Chat                                   getChat()                                   Conversation the message belongs to
 * @method User                                   getForwardFrom()                            Optional. For forwarded messages, sender of the original message
 * @method Chat                                   getForwardFromChat()                        Optional. For messages forwarded from a channel, information about the original channel
 * @method int                                    getForwardFromMessageId()                   Optional. For forwarded channel posts, identifier of the original message in the channel
 * @method string                                 getForwardSignature()                       Optional. For messages forwarded from channels, signature of the post author if present
 * @method string                                 getForwardSenderName()                      Optional. Sender's name for messages forwarded from users who disallow adding a link to their account in forwarded messages
 * @method int                                    getForwardDate()                            Optional. For forwarded messages, date the original message was sent in Unix time
 * @method bool                                   getIsTopicMessage()                         Optional. True, if the message is sent to a forum topic
 * @method bool                                   getIsAutomaticForward()                     Optional. True, if the message is a channel post that was automatically forwarded to the connected discussion group
 * @method ReplyToMessage                         getReplyToMessage()                         Optional. For replies, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
 * @method User                                   getViaBot()                                 Optional. Bot through which the message was sent
 * @method int                                    getEditDate()                               Optional. Date the message was last edited in Unix time
 * @method bool                                   getHasProtectedContent()                    Optional. True, if the message can't be forwarded
 * @method string                                 getMediaGroupId()                           Optional. The unique identifier of a media message group this message belongs to
 * @method string                                 getAuthorSignature()                        Optional. Signature of the post author for messages in channels
 * @method MessageEntity[]                        getEntities()                               Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @method MessageEntity[]                        getCaptionEntities()                        Optional. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
 * @method Audio                                  getAudio()                                  Optional. Message is an audio file, information about the file
 * @method Document                               getDocument()                               Optional. Message is a general file, information about the file
 * @method Animation                              getAnimation()                              Optional. Message is an animation, information about the animation. For backward compatibility, when this field is set, the document field will also be set
 * @method Game                                   getGame()                                   Optional. Message is a game, information about the game.
 * @method PhotoSize[]                            getPhoto()                                  Optional. Message is a photo, available sizes of the photo
 * @method Sticker                                getSticker()                                Optional. Message is a sticker, information about the sticker
 * @method Video                                  getVideo()                                  Optional. Message is a video, information about the video
 * @method Voice                                  getVoice()                                  Optional. Message is a voice message, information about the file
 * @method VideoNote                              getVideoNote()                              Optional. Message is a video note message, information about the video
 * @method string                                 getCaption()                                Optional. Caption for the document, photo or video, 0-200 characters
 * @method Contact                                getContact()                                Optional. Message is a shared contact, information about the contact
 * @method Location                               getLocation()                               Optional. Message is a shared location, information about the location
 * @method Venue                                  getVenue()                                  Optional. Message is a venue, information about the venue
 * @method Poll                                   getPoll()                                   Optional. Message is a native poll, information about the poll
 * @method Dice                                   getDice()                                   Optional. Message is a dice with random value, 1-6 for “🎲” and “🎯” base emoji, 1-5 for “🏀” and “⚽” base emoji, 1-64 for “🎰” base emoji
 * @method User[]                                 getNewChatMembers()                         Optional. A new member(s) was added to the group, information about them (one of this members may be the bot itself)
 * @method User                                   getLeftChatMember()                         Optional. A member was removed from the group, information about them (this member may be the bot itself)
 * @method string                                 getNewChatTitle()                           Optional. A chat title was changed to this value
 * @method PhotoSize[]                            getNewChatPhoto()                           Optional. A chat photo was changed to this value
 * @method MessageAutoDeleteTimerChanged          getMessageAutoDeleteTimerChanged()          Optional. Service message: auto-delete timer settings changed in the chat
 * @method bool                                   getDeleteChatPhoto()                        Optional. Service message: the chat photo was deleted
 * @method bool                                   getGroupChatCreated()                       Optional. Service message: the group has been created
 * @method bool                                   getSupergroupChatCreated()                  Optional. Service message: the supergroup has been created. This field can't be received in a message coming through updates, because bot can’t be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
 * @method bool                                   getChannelChatCreated()                     Optional. Service message: the channel has been created. This field can't be received in a message coming through updates, because bot can’t be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
 * @method int                                    getMigrateToChatId()                        Optional. The group has been migrated to a supergroup with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method int                                    getMigrateFromChatId()                      Optional. The supergroup has been migrated from a group with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method Message                                getPinnedMessage()                          Optional. Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it is itself a reply.
 * @method Invoice                                getInvoice()                                Optional. Message is an invoice for a payment, information about the invoice.
 * @method SuccessfulPayment                      getSuccessfulPayment()                      Optional. Message is a service message about a successful payment, information about the payment.
 * @method string                                 getConnectedWebsite()                       Optional. The domain name of the website on which the user has logged in.
 * @method PassportData                           getPassportData()                           Optional. Telegram Passport data
 * @method ProximityAlertTriggered                getProximityAlertTriggered()                Optional. Service message. A user in the chat triggered another user's proximity alert while sharing Live Location.
 * @method ForumTopicCreated                      getForumTopicCreated()                      Optional. Service message: forum topic created
 * @method ForumTopicClosed                       getForumTopicClosed()                       Optional. Service message: forum topic closed
 * @method ForumTopicReopened                     getForumTopicReopened()                     Optional. Service message: forum topic reopened
 * @method VideoChatScheduled                     getVideoChatScheduled()                     Optional. Service message: voice chat scheduled
 * @method VideoChatStarted                       getVideoChatStarted()                       Optional. Service message: voice chat started
 * @method VideoChatEnded                         getVideoChatEnded()                         Optional. Service message: voice chat ended
 * @method VideoChatParticipantsInvited           getVideoChatParticipantsInvited()           Optional. Service message: new participants invited to a voice chat
 * @method WebAppData                             getWebAppData()                             Optional. Service message: data sent by a Web App
 * @method InlineKeyboard                         getReplyMarkup()                            Optional. Inline keyboard attached to the message. login_url buttons are represented as ordinary url buttons.
 */
class Message extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'from'                              => User::class,
            'sender_chat'                       => Chat::class,
            'chat'                              => Chat::class,
            'forward_from'                      => User::class,
            'forward_from_chat'                 => Chat::class,
            'reply_to_message'                  => ReplyToMessage::class,
            'via_bot'                           => User::class,
            'entities'                          => [MessageEntity::class],
            'caption_entities'                  => [MessageEntity::class],
            'audio'                             => Audio::class,
            'document'                          => Document::class,
            'animation'                         => Animation::class,
            'game'                              => Game::class,
            'photo'                             => [PhotoSize::class],
            'sticker'                           => Sticker::class,
            'video'                             => Video::class,
            'voice'                             => Voice::class,
            'video_note'                        => VideoNote::class,
            'contact'                           => Contact::class,
            'location'                          => Location::class,
            'venue'                             => Venue::class,
            'poll'                              => Poll::class,
            'dice'                              => Dice::class,
            'new_chat_members'                  => [User::class],
            'left_chat_member'                  => User::class,
            'new_chat_photo'                    => [PhotoSize::class],
            'message_auto_delete_timer_changed' => MessageAutoDeleteTimerChanged::class,
            'pinned_message'                    => __CLASS__,
            'invoice'                           => Invoice::class,
            'successful_payment'                => SuccessfulPayment::class,
            'passport_data'                     => PassportData::class,
            'proximity_alert_triggered'         => ProximityAlertTriggered::class,
            'forum_topic_created'               => ForumTopicCreated::class,
            'forum_topic_closed'                => ForumTopicClosed::class,
            'forum_topic_reopened'              => ForumTopicReopened::class,
            'video_chat_scheduled'              => VideoChatScheduled::class,
            'video_chat_started'                => VideoChatStarted::class,
            'video_chat_ended'                  => VideoChatEnded::class,
            'video_chat_participants_invited'   => VideoChatParticipantsInvited::class,
            'web_app_data'                      => WebAppData::class,
            'reply_markup'                      => InlineKeyboard::class,
        ];
    }

    /**
     * return the entire command like /echo or /echo@bot1 if specified
     *
     * @return string|null
     */
    public function getFullCommand(): ?string
    {
        $text = $this->getProperty('text') ?? '';
        if (strpos($text, '/') !== 0) {
            return null;
        }

        $no_EOL = strtok($text, PHP_EOL);
        $no_space = strtok($text, ' ');

        //try to understand which separator \n or space divide /command from text
        return strlen($no_space) < strlen($no_EOL) ? $no_space : $no_EOL;
    }

    /**
     * Get command
     *
     * @return string|null
     */
    public function getCommand(): ?string
    {
        if ($command = $this->getProperty('command')) {
            return $command;
        }

        $full_command = $this->getFullCommand() ?? '';
        if (strpos($full_command, '/') !== 0) {
            return null;
        }
        $full_command = substr($full_command, 1);

        //check if command is followed by bot username
        $split_cmd = explode('@', $full_command);
        if (! isset($split_cmd[1])) {
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
     * @param  bool  $without_cmd
     *
     * @return string|null
     */
    public function getText($without_cmd = false): ?string
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
     */
    public function botAddedInChat(): bool
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
    public function getType(): string
    {
        $types = [
            'text',
            'audio',
            'animation',
            'document',
            'game',
            'photo',
            'sticker',
            'video',
            'voice',
            'video_note',
            'contact',
            'location',
            'venue',
            'poll',
            'new_chat_members',
            'left_chat_member',
            'new_chat_title',
            'new_chat_photo',
            'delete_chat_photo',
            'group_chat_created',
            'supergroup_chat_created',
            'channel_chat_created',
            'message_auto_delete_timer_changed',
            'migrate_to_chat_id',
            'migrate_from_chat_id',
            'pinned_message',
            'invoice',
            'successful_payment',
            'passport_data',
            'proximity_alert_triggered',
            'video_chat_scheduled',
            'video_chat_started',
            'video_chat_ended',
            'video_chat_participants_invited',
            'web_app_data',
            'reply_markup',
        ];

        $is_command = $this->getCommand() !== null;
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
