<?php

namespace PhpTelegramBot\Core\Entities\InlineQueryResult;

use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;
use PhpTelegramBot\Core\Entities\InputMessageContent\InputMessageContent;

/**
 * @method float                     getLatitude()            Latitude of the venue location in degrees
 * @method float                     getLongitude()           Longitude of the venue location in degrees
 * @method string                    getTitle()               Title of the venue
 * @method string                    getAddress()             Address of the venue
 * @method string|null               getFoursquareId()        Optional. Foursquare identifier of the venue if known
 * @method string|null               getFoursquareType()      Optional. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.).
 * @method string|null               getGooglePlaceId()       Optional. Google Places identifier of the venue
 * @method string|null               getGooglePlaceType()     Optional. Google Places type of the venue. (See supported types.).
 * @method InlineKeyboardMarkup|null getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent|null  getInputMessageContent() Optional. Content of the message to be sent instead of the venue
 * @method string|null               getThumbnailUrl()        Optional. Url of the thumbnail for the result
 * @method int|null                  getThumbnailWidth()      Optional. Thumbnail width
 * @method int|null                  getThumbnailHeight()     Optional. Thumbnail height
 */
class InlineQueryResultVenue extends InlineQueryResult
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
            'type' => 'venue',
        ];
    }
}
