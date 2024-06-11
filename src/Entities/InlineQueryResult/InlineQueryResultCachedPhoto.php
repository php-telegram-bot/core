<?php

namespace PhpTelegramBot\Core\Entities\InlineQueryResult;

use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;
use PhpTelegramBot\Core\Entities\InputMessageContent\InputMessageContent;
use PhpTelegramBot\Core\Entities\MessageEntity;

/**
 * @method string                    getPhotoFileId()           A valid file identifier of the photo
 * @method string|null               getTitle()                 Optional. Title for the result
 * @method string|null               getDescription()           Optional. Short description of the result
 * @method string|null               getCaption()               Optional. Caption of the photo to be sent, 0-1024 characters after entities parsing
 * @method string|null               getParseMode()             Optional. Mode for parsing entities in the photo caption. See formatting options for more details.
 * @method MessageEntity[]|null      getCaptionEntities()       Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 * @method bool|null                 getShowCaptionAboveMedia() Optional. Pass True, if the caption must be shown above the message media
 * @method InlineKeyboardMarkup|null getReplyMarkup()           Optional. Inline keyboard attached to the message
 * @method InputMessageContent|null  getInputMessageContent()   Optional. Content of the message to be sent instead of the photo
 */
class InlineQueryResultCachedPhoto extends InlineQueryResult
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
            'type' => 'photo',
        ];
    }
}
