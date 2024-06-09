<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string          getId()              Unique query identifier
 * @method User            getFrom()            User who sent the query
 * @method string          getInvoicePayload()  Bot specified invoice payload
 * @method ShippingAddress getShippingAddress() User specified shipping address
 */
class ShippingQuery extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'from' => User::class,
            'shipping_address' => ShippingAddress::class,
        ];
    }
}
