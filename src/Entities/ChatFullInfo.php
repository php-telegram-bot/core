<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\ReactionType\ReactionType;

/**
 * @method int                       getId()                              Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method string                    getType()                            Type of the chat, can be either “private”, “group”, “supergroup” or “channel”
 * @method string|null               getTitle()                           Optional. Title, for supergroups, channels and group chats
 * @method string|null               getUsername()                        Optional. Username, for private chats, supergroups and channels if available
 * @method string|null               getFirstName()                       Optional. First name of the other party in a private chat
 * @method string|null               getLastName()                        Optional. Last name of the other party in a private chat
 * @method bool                      isForum()                            Optional. True, if the supergroup chat is a forum (has topics enabled).
 * @method int                       getAccentColorId()                   Identifier of the accent color for the chat name and backgrounds of the chat photo, reply header, and link preview. See accent colors for more details.
 * @method int                       getMaxReactionCount()                The maximum number of reactions that can be set on a message in the chat
 * @method ChatPhoto|null            getPhoto()                           Optional. Chat photo
 * @method string[]|null             getActiveUsernames()                 Optional. If non-empty, the list of all active chat usernames; for private chats, supergroups and channels
 * @method Birthdate|null            getBirthdate()                       Optional. For private chats, the date of birth of the user
 * @method BusinessIntro|null        getBusinessIntro()                   Optional. For private chats with business accounts, the intro of the business
 * @method BusinessLocation|null     getBusinessLocation()                Optional. For private chats with business accounts, the location of the business
 * @method BusinessOpeningHours|null getBusinessOpeningHours()            Optional. For private chats with business accounts, the opening hours of the business
 * @method Chat|null                 getPersonalChat()                    Optional. For private chats, the personal channel of the user
 * @method ReactionType[]|null       getAvailableReactions()              Optional. List of available reactions allowed in the chat. If omitted, then all emoji reactions are allowed.
 * @method string|null               getBackgroundCustomEmojiId()         Optional. Custom emoji identifier of the emoji chosen by the chat for the reply header and link preview background
 * @method int|null                  getProfileAccentColorId()            Optional. Identifier of the accent color for the chat's profile background. See profile accent colors for more details.
 * @method string|null               getProfileBackgroundCustomEmojiId()  Optional. Custom emoji identifier of the emoji chosen by the chat for its profile background
 * @method string|null               getEmojiStatusCustomEmojiId()        Optional. Custom emoji identifier of the emoji status of the chat or the other party in a private chat
 * @method int|null                  getEmojiStatusExpirationDate()       Optional. Expiration date of the emoji status of the chat or the other party in a private chat, in Unix time, if any
 * @method string|null               getBio()                             Optional. Bio of the other party in a private chat
 * @method bool                      hasPrivateForwards()                 Optional. True, if privacy settings of the other party in the private chat allows to use tg://user?id=<user_id> links only in chats with the user
 * @method bool                      hasRestrictedVoiceAndVideoMessages() Optional. True, if the privacy settings of the other party restrict sending voice and video note messages in the private chat
 * @method true|null                 getJoinToSendMessages()              Optional. True, if users need to join the supergroup before they can send messages
 * @method true|null                 getJoinByRequest()                   Optional. True, if all users directly joining the supergroup without using an invite link need to be approved by supergroup administrators
 * @method string|null               getDescription()                     Optional. Description, for groups, supergroups and channel chats
 * @method string|null               getInviteLink()                      Optional. Primary invite link, for groups, supergroups and channel chats
 * @method Message|null              getPinnedMessage()                   Optional. The most recent pinned message (by sending date).
 * @method ChatPermissions|null      getPermissions()                     Optional. Default chat member permissions, for groups and supergroups
 * @method int|null                  getSlowModeDelay()                   Optional. For supergroups, the minimum allowed delay between consecutive messages sent by each unprivileged user; in seconds
 * @method int|null                  getUnrestrictBoostCount()            Optional. For supergroups, the minimum number of boosts that a non-administrator user needs to add in order to ignore slow mode and chat permissions
 * @method int|null                  getMessageAutoDeleteTime()           Optional. The time after which all messages sent to the chat will be automatically deleted; in seconds
 * @method bool                      hasAggressiveAntiSpamEnabled()       Optional. True, if aggressive anti-spam checks are enabled in the supergroup. The field is only available to chat administrators.
 * @method bool                      hasHiddenMembers()                   Optional. True, if non-administrators can only get the list of bots and administrators in the chat
 * @method bool                      hasProtectedContent()                Optional. True, if messages from the chat can't be forwarded to other chats
 * @method bool                      hasVisibleHistory()                  Optional. True, if new chat members will have access to old messages; available only to chat administrators
 * @method string|null               getStickerSetName()                  Optional. For supergroups, name of the group sticker set
 * @method bool                      canSetStickerSet()                   Optional. True, if the bot can change the group sticker set
 * @method string|null               getCustomEmojiStickerSetName()       Optional. For supergroups, the name of the group's custom emoji sticker set. Custom emoji from this set can be used by all users and bots in the group.
 * @method int|null                  getLinkedChatId()                    Optional. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice versa; for supergroups and channel chats. This identifier may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method ChatLocation|null         getLocation()                        Optional. For supergroups, the location to which the supergroup is connected
 */
class ChatFullInfo extends Entity implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'photo'                  => ChatPhoto::class,
            'birthdate'              => Birthdate::class,
            'business_intro'         => BusinessIntro::class,
            'business_location'      => BusinessLocation::class,
            'business_opening_hours' => BusinessOpeningHoursInterval::class,
            'personal_chat'          => Chat::class,
            'available_reactions'    => [ReactionType::class],
            'pinned_message'         => Message::class,
            'permissions'            => ChatPermissions::class,
            'location'               => ChatLocation::class,
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'is_forum'                                => false,
            'has_private_forwards'                    => false,
            'has_restricted_voice_and_video_messages' => false,
            'has_aggressive_anti_spam_enabled'        => false,
            'has_hidden_members'                      => false,
            'has_protected_content'                   => false,
            'has_visible_history'                     => false,
            'can_set_sticker_set'                     => false,
        ];
    }
}
