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
 * Class OrderInfo
 *
 * This object represents information about an order.
 *
 * @link https://core.telegram.org/bots/api#orderinfo
 *
 * @method string          getName()            Optional. User name
 * @method string          getPhoneNumber()     Optional. User's phone number
 * @method string          getEmail()           Optional. User email
 * @method ShippingAddress getShippingAddress() Optional. User shipping address
 **/
class OrderInfo extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'shipping_address' => ShippingAddress::class,
        ];
    }
}
