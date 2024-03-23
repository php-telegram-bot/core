<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

/**
 * This object contains information about the quoted part of a message that is replied to by the given message.
 *
 * @link https://core.telegram.org/bots/api#textquote
 *
 * @method string          getText()     Text of the quoted part of a message that is replied to by the given message
 * @method MessageEntity[] getEntities() Optional. Special entities that appear in the quote. Currently, only bold, italic, underline, strikethrough, spoiler, and custom_emoji entities are kept in quotes.
 * @method int             getPosition() Approximate quote position in the original message in UTF-16 code units as specified by the sender
 * @method bool            getIsManual() Optional. True, if the quote was chosen manually by the message sender. Otherwise, the quote was added automatically by the server.
 */
class TextQuote extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'entities' => [MessageEntity::class],
        ];
    }
}
