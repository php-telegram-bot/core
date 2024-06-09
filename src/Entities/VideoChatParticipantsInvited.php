<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method User[] getUsers() New members that were invited to the video chat
 */
class VideoChatParticipantsInvited extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'users' => [User::class],
        ];
    }
}
