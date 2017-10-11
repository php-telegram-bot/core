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
 * @property int       $id                              Unique identifier for this chat. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @property string    $type                            Type of chat, can be either "private", "group", "supergroup" or "channel"
 * @property string    $title                           Optional. Title, for channels and group chats
 * @property string    $username                        Optional. Username, for private chats, supergroups and channels if available
 * @property string    $first_name                      Optional. First name of the other party in a private chat
 * @property string    $last_name                       Optional. Last name of the other party in a private chat
 * @property bool      $all_members_are_administrators  Optional. True if a group has ‘All Members Are Admins’ enabled.
 * @property ChatPhoto $photo                           Optional. Chat photo. Returned only in getChat.
 * @property string    $description                     Optional. Description, for supergroups and channel chats. Returned only in getChat.
 * @property string    $invite_link                     Optional. Chat invite link, for supergroups and channel chats. Returned only in getChat.
 * @property string    $sticker_set_name                Optional. For supergroups, name of Group sticker set. Returned only in getChat.
 * @property bool      $can_set_sticker_set             Optional. True, if the bot can change group the sticker set. Returned only in getChat.
 * @method   int       getId()                          Unique identifier for this chat. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method   string    getType()                        Type of chat, can be either "private ", "group", "supergroup" or "channel"
 * @method   string    getTitle()                       Optional. Title, for channels and group chats
 * @method   string    getUsername()                    Optional. Username, for private chats, supergroups and channels if available
 * @method   string    getFirstName()                   Optional. First name of the other party in a private chat
 * @method   string    getLastName()                    Optional. Last name of the other party in a private chat
 * @method   bool      getAllMembersAreAdministrators() Optional. True if a group has ‘All Members Are Admins’ enabled.
 * @method   ChatPhoto getPhoto()                       Optional. Chat photo. Returned only in getChat.
 * @method   string    getDescription()                 Optional. Description, for supergroups and channel chats. Returned only in getChat.
 * @method   string    getInviteLink()                  Optional. Chat invite link, for supergroups and channel chats. Returned only in getChat.
 * @method   Message   getPinnedMessage()               Optional. Pinned message, for supergroups. Returned only in getChat.
 * @method   string    getStickerSetName()              Optional. For supergroups, name of Group sticker set. Returned only in getChat.
 * @method   bool      getCanSetStickerSet()            Optional. True, if the bot can change group the sticker set. Returned only in getChat.
 */
class Chat extends Entity
{
    /**
     * {@inheritdoc}
     */
    public function subEntities()
    {
        return [
            'photo'          => ChatPhoto::class,
            'pinned_message' => Message::class,
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
     * @return string|null
     */
    public function tryMention($escape_markdown = false)
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
    public function isGroupChat()
    {
        return $this->getType() === 'group' || $this->getId() < 0;
    }

    /**
     * Check if this is a private chat
     *
     * @return bool
     */
    public function isPrivateChat()
    {
        return $this->getType() === 'private';
    }

    /**
     * Check if this is a super group
     *
     * @return bool
     */
    public function isSuperGroup()
    {
        return $this->getType() === 'supergroup';
    }

    /**
     * Check if this is a channel
     *
     * @return bool
     */
    public function isChannel()
    {
        return $this->getType() === 'channel';
    }
}
