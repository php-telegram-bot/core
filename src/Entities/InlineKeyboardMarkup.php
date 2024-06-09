<?php

namespace PhpTelegramBot\Core\Entities;

class InlineKeyboardMarkup extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'inline_keyboard' => [[InlineKeyboardButton::class]],
        ];
    }
}
