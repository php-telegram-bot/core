<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\BackgroundType\BackgroundType;

/**
 * @method BackgroundType getType() Type of the background
 */
class ChatBackground extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'type' => BackgroundType::class,
        ];
    }
}
