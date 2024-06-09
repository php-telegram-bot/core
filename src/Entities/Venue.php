<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method Location    getLocation()                                                                                                                                                          Venue location. Can't be a live location
 * @method string      getTitle()                                                                                                                                                             Name of the venue
 * @method string      getAddress()                                                                                                                                                           Address of the venue
 * @method string|null getFoursquareId()                                                                                                                                                      Optional. Foursquare identifier of the venue
 * @method string|null getFoursquareType() Optional. Foursquare type of the venue. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
 * @method string|null getGooglePlaceId()                                                                                                                                                     Optional. Google Places identifier of the venue
 * @method string|null getGooglePlaceType() Optional. Google Places type of the venue. (See supported types.)
 */
class Venue extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'location' => Location::class,
        ];
    }
}
