<?php

namespace Longman\TelegramBot\Entities\ChatMember;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\User;

/**
 * Class ChatMemberOwner
 *
 * @link https://core.telegram.org/bots/api#chatmemberowner
 *
 * @method string getStatus()      The member's status in the chat, always “creator”
 * @method User   getUser()        Information about the user
 * @method string getCustomTitle() Custom title for this user
 * @method bool   getIsAnonymous() True, if the user's presence in the chat is hidden
 */
class ChatMemberOwner extends Entity implements ChatMember
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
