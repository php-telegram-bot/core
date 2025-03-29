<?php

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Entities\ReactionType\Factory as ReactionTypeFactory;
use Longman\TelegramBot\Entities\ReactionType\ReactionType;

/**
 * Represents a reaction added to a message along with the number of times it was added.
 *
 * @link https://core.telegram.org/bots/api#reactioncount
 *
 * @method ReactionType getType()       Type of the reaction
 * @method int          getTotalCount() Number of times the reaction was added
 */
class ReactionCount extends Entity
{
    protected function subEntities(): array
    {
        return [
            'type' => ReactionTypeFactory::class,
        ];
    }
}
