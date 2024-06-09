<?php

namespace PhpTelegramBot\Core\Entities\ChatMember;

use PhpTelegramBot\Core\Entities\User;

/**
 * @method User        getUser()                Information about the user
 * @method bool        getCanBeEdited()         True, if the bot is allowed to edit administrator privileges of that user
 * @method bool        getIsAnonymous()         True, if the user's presence in the chat is hidden
 * @method bool        getCanManageChat()       True, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages and ignore slow mode. Implied by any other administrator privilege.
 * @method bool        getCanDeleteMessages()   True, if the administrator can delete messages of other users
 * @method bool        getCanManageVideoChats() True, if the administrator can manage video chats
 * @method bool        getCanRestrictMembers()  True, if the administrator can restrict, ban or unban chat members, or access supergroup statistics
 * @method bool        getCanPromoteMembers()   True, if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by the user).
 * @method bool        getCanChangeInfo()       True, if the user is allowed to change the chat title, photo and other settings
 * @method bool        getCanInviteUsers()      True, if the user is allowed to invite new users to the chat
 * @method bool        getCanPostStories()      True, if the administrator can post stories to the chat
 * @method bool        getCanEditStories()      True, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
 * @method bool        getCanDeleteStories()    True, if the administrator can delete stories posted by other users
 * @method bool|null   getCanPostMessages()     Optional. True, if the administrator can post messages in the channel, or access channel statistics; for channels only
 * @method bool|null   getCanEditMessages()     Optional. True, if the administrator can edit messages of other users and can pin messages; for channels only
 * @method bool|null   getCanPinMessages()      Optional. True, if the user is allowed to pin messages; for groups and supergroups only
 * @method bool|null   getCanManageTopics()     Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
 * @method string|null getCustomTitle()         Optional. Custom title for this user
 */
class ChatMemberAdministrator extends ChatMember
{
    protected static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
