<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method array<array> getInlineKeyboard() Array of button rows, each represented by an Array of InlineKeyboardButton objects
 */
class InlineKeyboardMarkup extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'inline_keyboard' => [[InlineKeyboardButton::class]],
        ];
    }
}
