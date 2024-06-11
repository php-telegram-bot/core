<?php

namespace PhpTelegramBot\Core\Entities\InlineQueryResult;

use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;

/**
 * @method string                    getGameShortName() Short name of the game
 * @method InlineKeyboardMarkup|null getReplyMarkup()   Optional. Inline keyboard attached to the message
 */
class InlineQueryResultGame extends InlineQueryResult
{
    protected static function subEntities(): array
    {
        return [
            'reply_markup' => InlineKeyboardMarkup::class,
        ];
    }

    protected static function presetData(): array
    {
        return [
            'type' => 'game',
        ];
    }
}
