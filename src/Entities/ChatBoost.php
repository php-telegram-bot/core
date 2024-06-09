<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\ChatBoostSource\ChatBoostSource;

/**
 * @method string          getBoostId()        Unique identifier of the boost
 * @method int             getAddDate()        Point in time (Unix timestamp), when the chat was boosted
 * @method int             getExpirationDate() Point in time (Unix timestamp), when the boost will automatically expire, unless the booster's Telegram Premium subscription is prolonged
 * @method ChatBoostSource getSource()         Source of the added boost
 */
class ChatBoost extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'source' => ChatBoostSource::class,
        ];
    }
}
