<?php

namespace PhpTelegramBot\Core\Entities\InlineQueryResult;

use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;
use PhpTelegramBot\Core\Entities\InputMessageContent\InputMessageContent;

/**
 * @method string                    getTitle()               Title of the result
 * @method InputMessageContent       getInputMessageContent() Content of the message to be sent
 * @method InlineKeyboardMarkup|null getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method string|null               getUrl()                 Optional. URL of the result
 * @method bool|null                 getHideUrl()             Optional. Pass True if you don't want the URL to be shown in the message
 * @method string|null               getDescription()         Optional. Short description of the result
 * @method string|null               getThumbnailUrl()        Optional. Url of the thumbnail for the result
 * @method int|null                  getThumbnailWidth()      Optional. Thumbnail width
 * @method int|null                  getThumbnailHeight()     Optional. Thumbnail height
 */
class InlineQueryResultArticle extends InlineQueryResult
{
    protected static function subEntities(): array
    {
        return [
            'input_message_content' => InputMessageContent::class,
            'reply_markup'          => InlineKeyboardMarkup::class,
        ];
    }

    protected static function presetData(): array
    {
        return [
            'type' => 'article',
        ];
    }
}
