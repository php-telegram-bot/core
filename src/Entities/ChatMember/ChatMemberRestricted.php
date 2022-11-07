<?php

namespace Longman\TelegramBot\Entities\ChatMember;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\User;

/**
 * Class ChatMemberRestricted
 *
 * @link https://core.telegram.org/bots/api#chatmemberrestricted
 *
 * @method string getStatus()                The member's status in the chat, always “restricted”
 * @method User   getUser()                  Information about the user
 * @method bool   getIsMember()              True, if the user is a member of the chat at the moment of the request
 * @method bool   getCanChangeInfo()         True, if the user is allowed to change the chat title, photo and other settings
 * @method bool   getCanInviteUsers()        True, if the user is allowed to invite new users to the chat
 * @method bool   getCanPinMessages()        True, if the user is allowed to pin messages; groups and supergroups only
 * @method bool   getCanManageTopics()       True, if the user is allowed to create forum topics
 * @method bool   getCanSendMessages()       True, if the user is allowed to send text messages, contacts, locations and venues
 * @method bool   getCanSendMediaMessages()  True, if the user is allowed to send audios, documents, photos, videos, video notes and voice notes
 * @method bool   getCanSendPolls()          True, if the user is allowed to send polls
 * @method bool   getCanSendOtherMessages()  True, if the user is allowed to send animations, games, stickers and use inline bots
 * @method bool   getCanAddWebPagePreviews() True, if the user is allowed to add web page previews to their messages
 * @method int    getUntilDate()             Date when restrictions will be lifted for this user; unix time
 */
class ChatMemberRestricted extends Entity implements ChatMember
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
