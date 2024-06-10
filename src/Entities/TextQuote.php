<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

/**
 * @method string               getText()     Text of the quoted part of a message that is replied to by the given message
 * @method MessageEntity[]|null getEntities() Optional. Special entities that appear in the quote. Currently, only bold, italic, underline, strikethrough, spoiler, and custom_emoji entities are kept in quotes.
 * @method int                  getPosition() Approximate quote position in the original message in UTF-16 code units as specified by the sender
 * @method bool                 isManual()    Optional. True, if the quote was chosen manually by the message sender. Otherwise, the quote was added automatically by the server.
 */
class TextQuote extends Entity implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'entities' => [MessageEntity::class],
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'is_manual' => false,
        ];
    }
}
