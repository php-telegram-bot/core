<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

/**
 * Class Venue
 *
 * @link https://core.telegram.org/bots/api#venue
 *
 * @method Location getLocation()        Venue location
 * @method string   getTitle()           Name of the venue
 * @method string   getAddress()         Address of the venue
 * @method string   getFoursquareId()    Optional. Foursquare identifier of the venue
 * @method string   getFoursquareType()  Optional. Foursquare type of the venue. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
 * @method string   getGooglePlaceId()   Optional. Google Places identifier of the venue
 * @method string   getGooglePlaceType() Optional. Google Places type of the venue
 */
class Venue extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'location' => Location::class,
        ];
    }
}
