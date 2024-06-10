<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string         getId()               Unique query identifier
 * @method User           getFrom()             User who sent the query
 * @method string         getCurrency()         Three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars
 * @method int            getTotalAmount()      Total price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
 * @method string         getInvoicePayload()   Bot specified invoice payload
 * @method string|null    getShippingOptionId() Optional. Identifier of the shipping option chosen by the user
 * @method OrderInfo|null getOrderInfo()        Optional. Order information provided by the user
 */
class PreCheckoutQuery extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'from'       => User::class,
            'order_info' => OrderInfo::class,
        ];
    }
}
