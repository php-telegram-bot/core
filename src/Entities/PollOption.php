<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string               getText()         Option text, 1-100 characters
 * @method MessageEntity[]|null getTextEntities() Optional. Special entities that appear in the option text. Currently, only custom emoji entities are allowed in poll option texts
 * @method int                  getVoterCount()   Number of users that voted for this option
 */
class PollOption extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'text_entities' => [MessageEntity::class],
        ];
    }
}
