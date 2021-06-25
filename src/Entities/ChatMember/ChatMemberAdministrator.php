<?php


namespace Longman\TelegramBot\Entities\ChatMember;


use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\User;

/**
 * Class ChatMemberAdministrator
 *
 * @link https://core.telegram.org/bots/api#chatmemberadministrator
 *
 * @method string getStatus()              The member's status in the chat, always “administrator”
 * @method User   getUser()                Information about the user
 * @method bool   getCanBeEdited()         True, if the bot is allowed to edit administrator privileges of that user
 * @method string getCustomTitle()         Custom title for this user
 * @method bool   getIsAnonymous()         True, if the user's presence in the chat is hidden
 * @method bool   getCanManageChat()       True, if the administrator can access the chat event log, chat statistics, message statistics in channels, see channel members, see anonymous administrators in supergroups and ignore slow mode. Implied by any other administrator privilege
 * @method bool   getCanPostMessages()     True, if the administrator can post in the channel; channels only
 * @method bool   getCanEditMessages()     True, if the administrator can edit messages of other users and can pin messages; channels only
 * @method bool   getCanDeleteMessages()   True, if the administrator can delete messages of other users
 * @method bool   getCanManageVoiceChats() True, if the administrator can manage voice chats
 * @method bool   getCanRestrictMembers()  True, if the administrator can restrict, ban or unban chat members
 * @method bool   getCanPromoteMembers()   True, if the administrator can add new administrators with a subset of their own privileges or demote administrators that he has promoted, directly or indirectly (promoted by administrators that were appointed by the user)
 * @method bool   getCanChangeInfo()       True, if the user is allowed to change the chat title, photo and other settings
 * @method bool   getCanInviteUsers()      True, if the user is allowed to invite new users to the chat
 * @method bool   getCanPinMessages()      True, if the user is allowed to pin messages; groups and supergroups only
 */
class ChatMemberAdministrator extends Entity implements ChatMember
{
    /**
     * @inheritDoc
     */
    protected function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }

}
