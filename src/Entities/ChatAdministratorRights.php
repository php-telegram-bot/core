<?php

namespace Longman\TelegramBot\Entities;

/**
 * Represents the rights of an administrator in a chat.
 *
 * @method bool getIsAnonymous()         True, if the user's presence in the chat is hidden
 * @method bool getCanManageChat()       True, if the administrator can access the chat event log, chat statistics, message statistics in channels, see channel members, see anonymous administrators in supergroups and ignore slow mode. Implied by any other administrator privilege
 * @method bool getCanDeleteMessages()   True, if the administrator can delete messages of other users
 * @method bool getCanManageVideoChats() True, if the administrator can manage video chats
 * @method bool getCanRestrictMembers()  True, if the administrator can restrict, ban or unban chat members
 * @method bool getCanPromoteMembers()   True, if the administrator can add new administrators with a subset of their own privileges or demote administrators that he has promoted, directly or indirectly (promoted by administrators that were appointed by the user)
 * @method bool getCanChangeInfo()       True, if the user is allowed to change the chat title, photo and other settings
 * @method bool getCanInviteUsers()      True, if the user is allowed to invite new users to the chat
 * @method bool getCanPostMessages()     Optional. True, if the administrator can post in the channel; channels only
 * @method bool getCanEditMessages()     Optional. True, if the administrator can edit messages of other users and can pin messages; channels only
 * @method bool getCanPinMessages()      Optional. True, if the user is allowed to pin messages; groups and supergroups only
 * @method bool getCanManageTopics()     Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; supergroups only
 *
 * @method $this setIsAnonymous(bool $is_anonymous)                   True, if the user's presence in the chat is hidden
 * @method $this setCanManageChat(bool $can_manage_chat)              True, if the administrator can access the chat event log, chat statistics, message statistics in channels, see channel members, see anonymous administrators in supergroups and ignore slow mode. Implied by any other administrator privilege
 * @method $this setCanDeleteMessages(bool $can_delete_messages)      True, if the administrator can delete messages of other users
 * @method $this setCanManageVideoChats(bool $can_manage_video_chats) True, if the administrator can manage video chats
 * @method $this setCanRestrictMembers(bool $can_restrict_members)    True, if the administrator can restrict, ban or unban chat members
 * @method $this setCanPromoteMembers(bool $can_promote_members)      True, if the administrator can add new administrators with a subset of their own privileges or demote administrators that he has promoted, directly or indirectly (promoted by administrators that were appointed by the user)
 * @method $this setCanChangeInfo(bool $can_change_info)              True, if the user is allowed to change the chat title, photo and other settings
 * @method $this setCanInviteUsers(bool $can_invite_users)            True, if the user is allowed to invite new users to the chat
 * @method $this setCanPostMessages(bool $can_post_messages)          Optional. True, if the administrator can post in the channel; channels only
 * @method $this setCanEditMessages(bool $can_edit_messages)          Optional. True, if the administrator can edit messages of other users and can pin messages; channels only
 * @method $this setCanPinMessages(bool $can_pin_messages)            Optional. True, if the user is allowed to pin messages; groups and supergroups only
 * @method $this setCanManageTopics(bool $can_manage_topics)          Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; supergroups only
 */
class ChatAdministratorRights extends Entity
{

}
