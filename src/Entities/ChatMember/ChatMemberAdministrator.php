<?php

namespace PhpTelegramBot\Core\Entities\ChatMember;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\User;

/**
 * @method User        getUser()             Information about the user
 * @method bool        canBeEdited()         True, if the bot is allowed to edit administrator privileges of that user
 * @method bool        isAnonymous()         True, if the user's presence in the chat is hidden
 * @method bool        canManageChat()       True, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages and ignore slow mode. Implied by any other administrator privilege.
 * @method bool        canDeleteMessages()   True, if the administrator can delete messages of other users
 * @method bool        canManageVideoChats() True, if the administrator can manage video chats
 * @method bool        canRestrictMembers()  True, if the administrator can restrict, ban or unban chat members, or access supergroup statistics
 * @method bool        canPromoteMembers()   True, if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by the user).
 * @method bool        canChangeInfo()       True, if the user is allowed to change the chat title, photo and other settings
 * @method bool        canInviteUsers()      True, if the user is allowed to invite new users to the chat
 * @method bool        canPostStories()      True, if the administrator can post stories to the chat
 * @method bool        canEditStories()      True, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
 * @method bool        canDeleteStories()    True, if the administrator can delete stories posted by other users
 * @method bool        canPostMessages()     Optional. True, if the administrator can post messages in the channel, or access channel statistics; for channels only
 * @method bool        canEditMessages()     Optional. True, if the administrator can edit messages of other users and can pin messages; for channels only
 * @method bool        canPinMessages()      Optional. True, if the user is allowed to pin messages; for groups and supergroups only
 * @method bool        canManageTopics()     Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
 * @method string|null getCustomTitle()      Optional. Custom title for this user
 */
class ChatMemberAdministrator extends ChatMember implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'can_be_edited'          => false,
            'is_anonymous'           => false,
            'can_manage_chat'        => false,
            'can_delete_messages'    => false,
            'can_manage_video_chats' => false,
            'can_restrict_members'   => false,
            'can_promote_members'    => false,
            'can_change_info'        => false,
            'can_invite_users'       => false,
            'can_post_stories'       => false,
            'can_edit_stories'       => false,
        ];
    }
}
