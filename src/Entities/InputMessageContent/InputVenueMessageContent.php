<?php

namespace PhpTelegramBot\Core\Entities\InputMessageContent;

/**
 * @method float       getLatitude()        Latitude of the venue in degrees
 * @method float       getLongitude()       Longitude of the venue in degrees
 * @method string      getTitle()           Name of the venue
 * @method string      getAddress()         Address of the venue
 * @method string|null getFoursquareId()    Optional. Foursquare identifier of the venue, if known
 * @method string|null getFoursquareType()  Optional. Foursquare type of the venue, if known. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.).
 * @method string|null getGooglePlaceId()   Optional. Google Places identifier of the venue
 * @method string|null getGooglePlaceType() Optional. Google Places type of the venue. (See supported types.).
 */
class InputVenueMessageContent extends InputMessageContent
{
    //
}
