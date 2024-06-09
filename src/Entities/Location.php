<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method float      getLatitude()             Latitude as defined by sender
 * @method float      getLongitude()            Longitude as defined by sender
 * @method float|null getHorizontalAccuracy()   Optional. The radius of uncertainty for the location, measured in meters; 0-1500
 * @method int|null   getLivePeriod()           Optional. Time relative to the message sending date, during which the location can be updated; in seconds. For active live locations only.
 * @method int|null   getHeading()              Optional. The direction in which user is moving, in degrees; 1-360. For active live locations only.
 * @method int|null   getProximityAlertRadius() Optional. The maximum distance for proximity alerts about approaching another chat member, in meters. For sent live locations only.
 */
class Location extends Entity
{
    //
}
