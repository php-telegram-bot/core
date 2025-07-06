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
 * Class ChatFullInfo
 *
 * This object contains full information about a chat.
 *
 * @link https://core.telegram.org/bots/api#chatfullinfo
 *
 * @method string          getDescription()                        Optional. Description, for groups, supergroups and channel chats.
 * @method string          getInviteLink()                         Optional. Primary invite link, for groups, supergroups and channel chats. Returned only in getChat.
 * @method Message         getPinnedMessage()                      Optional. The most recent pinned message (by sending date). Returned only in getChat.
 * @method ChatPermissions getPermissions()                        Optional. Default chat member permissions, for groups and supergroups. Returned only in getChat.
 * @method int             getSlowModeDelay()                      Optional. For supergroups, the minimum allowed delay between consecutive messages sent by each unprivileged user; in seconds. Returned only in getChat.
 * @method int             getUnrestrictBoostCount()               Optional. For supergroups, the minimum number of boosts that a non-administrator user needs to add in order to ignore slow mode and chat permissions.
 * @method int             getMessageAutoDeleteTime()              Optional. The time after which all messages sent to the chat will be automatically deleted; in seconds. Returned only in getChat.
 * @method bool            getHasAggressiveAntiSpamEnabled()       Optional. True, if aggressive anti-spam checks are enabled in the supergroup. The field is only available to chat administrators.
 * @method bool            getHasHiddenMembers()                   Optional. True, if non-administrators can only get the list of bots and administrators in the chat. Returned only in getChat.
 * @method bool            getHasProtectedContent()                Optional. True, if messages from the chat can't be forwarded to other chats. Returned only in getChat.
 * @method bool            getHasVisibleHistory()                  Optional. True, if new chat members will have access to old messages; available only to chat administrators.
 * @method string          getStickerSetName()                     Optional. For supergroups, name of group sticker set. Returned only in getChat.
 * @method bool            getCanSetStickerSet()                   Optional. True, if the bot can change the group sticker set. Returned only in getChat.
 * @method string          getCustomEmojiStickerSetName()          Optional. For supergroups, the name of the group's custom emoji sticker set. Custom emoji from this set can be used by all users and bots in the group.
 * @method int             getLinkedChatId()                       Optional. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice versa; for supergroups and channel chats. Returned only in getChat.
 * @method ChatLocation    getLocation()                           Optional. For supergroups, the location to which the supergroup is connected. Returned only in getChat.
 * @method int             getMaxReactionCount()                   Optional. The maximum number of reactions that can be set on a message in the chat
 */
class ChatFullInfo extends Chat
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return array_merge(parent::subEntities(), [
            // Properties already defined in Chat.php and handled by its subEntities are inherited.
            // Add or override here if ChatFullInfo has different types or new sub-entities.
            // For example, if PinnedMessage in ChatFullInfo could be a different class than in Chat,
            // or if new properties in ChatFullInfo are entities themselves.
            // Based on the current structure, most of these are already covered by Chat.php
        ]);
    }
}
