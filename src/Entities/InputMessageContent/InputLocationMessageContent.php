<?php

namespace PhpTelegramBot\Core\Entities\InputMessageContent;

/**
 * @method float      getLatitude()             Latitude of the location in degrees
 * @method float      getLongitude()            Longitude of the location in degrees
 * @method float|null getHorizontalAccuracy()   Optional. The radius of uncertainty for the location, measured in meters; 0-1500
 * @method int|null   getLivePeriod()           Optional. Period in seconds during which the location can be updated, should be between 60 and 86400, or 0x7FFFFFFF for live locations that can be edited indefinitely.
 * @method int|null   getHeading()              Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
 * @method int|null   getProximityAlertRadius() Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
 */
class InputLocationMessageContent extends InputMessageContent
{
    //
}
