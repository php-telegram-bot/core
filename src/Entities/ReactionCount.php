<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\ReactionType\ReactionType;

/**
 * @method ReactionType getType()       Type of the reaction
 * @method int          getTotalCount() Number of times the reaction was added
 */
class ReactionCount extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'type' => ReactionType::class,
        ];
    }
}
