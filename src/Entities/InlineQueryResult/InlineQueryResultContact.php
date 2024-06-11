<?php

namespace PhpTelegramBot\Core\Entities\InlineQueryResult;

use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;
use PhpTelegramBot\Core\Entities\InputMessageContent\InputMessageContent;

/**
 * @method string                    getPhoneNumber()         Contact's phone number
 * @method string                    getFirstName()           Contact's first name
 * @method string|null               getLastName()            Optional. Contact's last name
 * @method string|null               getVcard()               Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
 * @method InlineKeyboardMarkup|null getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent|null  getInputMessageContent() Optional. Content of the message to be sent instead of the contact
 * @method string|null               getThumbnailUrl()        Optional. Url of the thumbnail for the result
 * @method int|null                  getThumbnailWidth()      Optional. Thumbnail width
 * @method int|null                  getThumbnailHeight()     Optional. Thumbnail height
 */
class InlineQueryResultContact extends InlineQueryResult
{
    protected static function subEntities(): array
    {
        return [
            'reply_markup'          => InlineKeyboardMarkup::class,
            'input_message_content' => InputMessageContent::class,
        ];
    }

    protected static function presetData(): array
    {
        return [
            'type' => 'contact',
        ];
    }
}
