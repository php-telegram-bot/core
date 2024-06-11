<?php

namespace PhpTelegramBot\Core\Entities\InlineQueryResult;

use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;
use PhpTelegramBot\Core\Entities\InputMessageContent\InputMessageContent;

/**
 * @method float                     getLatitude()             Location latitude in degrees
 * @method float                     getLongitude()            Location longitude in degrees
 * @method string                    getTitle()                Location title
 * @method float|null                getHorizontalAccuracy()   Optional. The radius of uncertainty for the location, measured in meters; 0-1500
 * @method int|null                  getLivePeriod()           Optional. Period in seconds during which the location can be updated, should be between 60 and 86400, or 0x7FFFFFFF for live locations that can be edited indefinitely.
 * @method int|null                  getHeading()              Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
 * @method int|null                  getProximityAlertRadius() Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
 * @method InlineKeyboardMarkup|null getReplyMarkup()          Optional. Inline keyboard attached to the message
 * @method InputMessageContent|null  getInputMessageContent()  Optional. Content of the message to be sent instead of the location
 * @method string|null               getThumbnailUrl()         Optional. Url of the thumbnail for the result
 * @method int|null                  getThumbnailWidth()       Optional. Thumbnail width
 * @method int|null                  getThumbnailHeight()      Optional. Thumbnail height
 */
class InlineQueryResultLocation extends InlineQueryResult
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
            'type' => 'location',
        ];
    }
}
