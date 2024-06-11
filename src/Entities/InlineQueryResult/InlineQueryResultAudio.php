<?php

namespace PhpTelegramBot\Core\Entities\InlineQueryResult;

use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;
use PhpTelegramBot\Core\Entities\InputMessageContent\InputMessageContent;
use PhpTelegramBot\Core\Entities\MessageEntity;

/**
 * @method string                    getAudioUrl()            A valid URL for the audio file
 * @method string                    getTitle()               Title
 * @method string|null               getCaption()             Optional. Caption, 0-1024 characters after entities parsing
 * @method string|null               getParseMode()           Optional. Mode for parsing entities in the audio caption. See formatting options for more details.
 * @method MessageEntity[]|null      getCaptionEntities()     Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 * @method string|null               getPerformer()           Optional. Performer
 * @method int|null                  getAudioDuration()       Optional. Audio duration in seconds
 * @method InlineKeyboardMarkup|null getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent|null  getInputMessageContent() Optional. Content of the message to be sent instead of the audio
 */
class InlineQueryResultAudio extends InlineQueryResult
{
    protected static function subEntities(): array
    {
        return [
            'caption_entities'      => [MessageEntity::class],
            'reply_markup'          => InlineKeyboardMarkup::class,
            'input_message_content' => InputMessageContent::class,
        ];
    }

    protected static function presetData(): array
    {
        return [
            'type' => 'audio',
        ];
    }
}
