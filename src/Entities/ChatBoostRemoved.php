<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\ChatBoostSource\ChatBoostSource;

/**
 * @method Chat            getChat()       Chat which was boosted
 * @method string          getBoostId()    Unique identifier of the boost
 * @method int             getRemoveDate() Point in time (Unix timestamp), when the boost was removed
 * @method ChatBoostSource getSource()     Source of the removed boost
 */
class ChatBoostRemoved extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'source' => ChatBoostSource::class,
        ];
    }
}
