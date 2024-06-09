<?php

namespace PhpTelegramBot\Core\Entities;

class InaccessibleMessage extends MaybeInaccessibleMessage
{
    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
        ];
    }
}
