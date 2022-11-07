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

/**
 * Class Chat
 *
 * @link https://core.telegram.org/bots/api#chat
 *
 * @property string type Type of chat, can be either "private ", "group", "supergroup" or "channel"
 *
 * @method int             getId()                                 Unique identifier for this chat. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method string          getType()                               Type of chat, can be either "private ", "group", "supergroup" or "channel"
 * @method string          getTitle()                              Optional. Title, for channels and group chats
 * @method string          getUsername()                           Optional. Username, for private chats, supergroups and channels if available
 * @method string          getFirstName()                          Optional. First name of the other party in a private chat
 * @method string          getLastName()                           Optional. Last name of the other party in a private chat
 * @method bool            getIsForum()                            Optional. True, if the supergroup chat is a forum (has topics enabled)
 * @method ChatPhoto       getPhoto()                              Optional. Chat photo. Returned only in getChat.
 * @method string[]        getActiveUsernames()                    Optional. If non-empty, the list of all active chat usernames; for private chats, supergroups and channels. Returned only in getChat.
 * @method string          getEmojiStatusCustomEmojiId()           Optional. Custom emoji identifier of emoji status of the other party in a private chat. Returned only in getChat.
 * @method string          getBio()                                Optional. Bio of the other party in a private chat. Returned only in getChat.
 * @method bool            getHasPrivateForwards()                 Optional. True, if privacy settings of the other party in the private chat allows to use tg://user?id=<user_id> links only in chats with the user. Returned only in getChat.
 * @method bool            getHasRestrictedVoiceAndVideoMessages() Optional. True, if the privacy settings of the other party restrict sending voice and video note messages in the private chat. Returned only in getChat.
 * @method bool            getJoinToSendMessages()                 Optional. True, if users need to join the supergroup before they can send messages. Returned only in getChat.
 * @method bool            getJoinByRequest()                      Optional. True, if all users directly joining the supergroup need to be approved by supergroup administrators. Returned only in getChat.
 * @method string          getDescription()                        Optional. Description, for groups, supergroups and channel chats. Returned only in getChat.
 * @method string          getInviteLink()                         Optional. Chat invite link, for groups, supergroups and channel chats. Each administrator in a chat generates their own invite links, so the bot must first generate the link using exportChatInviteLink. Returned only in getChat.
 * @method Message         getPinnedMessage()                      Optional. Pinned message, for groups, supergroups and channels. Returned only in getChat.
 * @method ChatPermissions getPermissions()                        Optional. Default chat member permissions, for groups and supergroups. Returned only in getChat.
 * @method int             getSlowModeDelay()                      Optional. For supergroups, the minimum allowed delay between consecutive messages sent by each unpriviledged user. Returned only in getChat.
 * @method int             getMessageAutoDeleteTime()              Optional. The time after which all messages sent to the chat will be automatically deleted; in seconds. Returned only in getChat.
 * @method bool            getHasProtectedContent()                Optional. True, if messages from the chat can't be forwarded to other chats. Returned only in getChat.
 * @method string          getStickerSetName()                     Optional. For supergroups, name of group sticker set. Returned only in getChat.
 * @method bool            getCanSetStickerSet()                   Optional. True, if the bot can change the group sticker set. Returned only in getChat.
 * @method int             getLinkedChatId()                       Optional. Unique identifier for the linked chat. Returned only in getChat.
 * @method ChatLocation    getLocation()                           Optional. For supergroups, the location to which the supergroup is connected. Returned only in getChat.
 */
class Chat extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'photo'          => ChatPhoto::class,
            'pinned_message' => Message::class,
            'permissions'    => ChatPermissions::class,
            'location'       => ChatLocation::class,
        ];
    }

    public function __construct($data)
    {
        parent::__construct($data);

        $id   = $this->getId();
        $type = $this->getType();
        if (!$type) {
            $id > 0 && $this->type = 'private';
            $id < 0 && $this->type = 'group';
        }
    }

    /**
     * Try to mention the user of this chat, else return the title
     *
     * @param bool $escape_markdown
     *
     * @return string
     */
    public function tryMention($escape_markdown = false): string
    {
        if ($this->isPrivateChat()) {
            return parent::tryMention($escape_markdown);
        }

        return $this->getTitle();
    }

    /**
     * Check if this is a group chat
     *
     * @return bool
     */
    public function isGroupChat(): bool
    {
        return $this->getType() === 'group';
    }

    /**
     * Check if this is a private chat
     *
     * @return bool
     */
    public function isPrivateChat(): bool
    {
        return $this->getType() === 'private';
    }

    /**
     * Check if this is a super group
     *
     * @return bool
     */
    public function isSuperGroup(): bool
    {
        return $this->getType() === 'supergroup';
    }

    /**
     * Check if this is a channel
     *
     * @return bool
     */
    public function isChannel(): bool
    {
        return $this->getType() === 'channel';
    }
}
