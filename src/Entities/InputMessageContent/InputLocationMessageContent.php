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
 *   'latitude'  => 36.0338,
 *   'longitude' => 71.8601,
 * ];
 *
 * @method float getLatitude()  Latitude of the location in degrees
 * @method float getLongitude() Longitude of the location in degrees
 *
 * @method $this setLatitude(float $latitude)   Latitude of the location in degrees
 * @method $this setLongitude(float $longitude) Longitude of the location in degrees
 */
class InputLocationMessageContent extends InlineEntity implements InputMessageContent
{

}
