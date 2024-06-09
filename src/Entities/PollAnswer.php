<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string    getPollId()    Unique poll identifier
 * @method Chat|null getVoterChat() Optional. The chat that changed the answer to the poll, if the voter is anonymous
 * @method User|null getUser()      Optional. The user that changed the answer to the poll, if the voter isn't anonymous
 * @method int[]     getOptionIds() 0-based identifiers of chosen answer options. May be empty if the vote was retracted.
 */
class PollAnswer extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'voter_chat' => Chat::class,
            'user' => User::class,
        ];
    }
}
