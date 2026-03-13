<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class LocationAddress
 *
 * Describes the physical address of a location.
 *
 * @link https://core.telegram.org/bots/api#locationaddress
 *
 * @method string getCountryCode() The two-letter ISO 3166-1 alpha-2 country code of the country where the location is located
 * @method string getState()       Optional. State of the location
 * @method string getCity()        Optional. City of the location
 * @method string getStreet()      Optional. Street address of the location
 */
class LocationAddress extends Entity
{
}
