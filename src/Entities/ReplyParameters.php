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
 * Describes reply parameters for the message that is being sent.
 *
 * @link https://core.telegram.org/bots/api#replyparameters
 *
 * @method int             getMessageId()                Identifier of the message that will be replied to in the current chat, or in the chat chat_id if it is specified
 * @method int|string      getChatId()                   Optional. If the message to be replied to is from a different chat, unique identifier for the chat or username of the channel (in the format @channelusername)
 * @method bool            getAllowSendingWithoutReply() Optional. Pass True if the message should be sent even if the specified message to be replied to is not found; can be used only for replies in the same chat and forum topic.
 * @method string          getQuote()                    Optional. Quoted part of the message to be replied to; 0-1024 characters after entities parsing. The quote must be an exact substring of the message to be replied to, including bold, italic, underline, strikethrough, spoiler, and custom_emoji entities. The message will fail to send if the quote isn't found in the original message.
 * @method string          getQuoteParseMode()           Optional. Mode for parsing entities in the quote. See formatting options for more details.
 * @method MessageEntity[] getQuoteEntities()            Optional. A JSON-serialized list of special entities that appear in the quote. It can be specified instead of quote_parse_mode.
 * @method int             getQuotePosition()            Optional. Position of the quote in the original message in UTF-16 code units
 */
class ReplyParameters extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'quote_entities' => [MessageEntity::class],
        ];
    }
}
