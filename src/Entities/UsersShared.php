<?php

namespace Longman\TelegramBot\Entities;

/**
 * This object contains information about the users whose identifiers were shared with the bot using a KeyboardButtonRequestUsers button.
 *
 * @link https://core.telegram.org/bots/api#usersshared
 *
 * @method int          getRequestId() Identifier of the request
 * @method SharedUser[] getUsers()     Information about users shared with the bot.
 */
class UsersShared extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'users' => [SharedUser::class],
        ];
    }
}
