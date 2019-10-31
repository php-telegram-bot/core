<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\Payments;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class ShippingAddress
 *
 * This object represents a shipping address.
 *
 * @link https://core.telegram.org/bots/api#shippingaddress
 *
 * @method string getCountryCode() ISO 3166-1 alpha-2 country code
 * @method string getState()       State, if applicable
 * @method string getCity()        City
 * @method string getStreetLine1() First line for the address
 * @method string getStreetLine2() Second line for the address
 * @method string getPostCode()    Address post code
 **/
class ShippingAddress extends Entity
{

}
