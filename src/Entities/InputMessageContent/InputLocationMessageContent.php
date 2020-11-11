<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\InputMessageContent;

use Longman\TelegramBot\Entities\InlineQuery\InlineEntity;

/**
 * Class InputLocationMessageContent
 *
 * @link https://core.telegram.org/bots/api#inputlocationmessagecontent
 *
 * <code>
 * $data = [
 *   'latitude'               => 36.0338,
 *   'longitude'              => 71.8601,
 *   'horizontal_accuracy'    => 36.9,
 *   'live_period'            => 900,
 *   'heading'                => 88,
 *   'proximity_alert_radius' => 300,
 * ];
 *
 * @method float getLatitude()             Latitude of the location in degrees
 * @method float getLongitude()            Longitude of the location in degrees
 * @method float getHorizontalAccuracy()   Optional. The radius of uncertainty for the location, measured in meters; 0-1500
 * @method int   getLivePeriod()           Optional. Period in seconds for which the location can be updated, should be between 60 and 86400.
 * @method int   getHeading()              Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
 * @method int   getProximityAlertRadius() Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
 *
 * @method $this setLatitude(float $latitude)                         Latitude of the location in degrees
 * @method $this setLongitude(float $longitude)                       Longitude of the location in degrees
 * @method $this setHorizontalAccuracy(float $horizontal_accuracy)    Optional. The radius of uncertainty for the location, measured in meters; 0-1500
 * @method $this setLivePeriod(int $live_period)                      Optional. Period in seconds for which the location can be updated, should be between 60 and 86400.
 * @method $this setHeading(int $heading)                             Optional. For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
 * @method $this setProximityAlertRadius(int $proximity_alert_radius) Optional. For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
 */
class InputLocationMessageContent extends InlineEntity implements InputMessageContent
{

}
