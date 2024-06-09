<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string|null          getName()            Optional. User name
 * @method string|null          getPhoneNumber()     Optional. User's phone number
 * @method string|null          getEmail()           Optional. User email
 * @method ShippingAddress|null getShippingAddress() Optional. User shipping address
 */
class OrderInfo extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'shipping_address' => ShippingAddress::class,
        ];
    }
}
