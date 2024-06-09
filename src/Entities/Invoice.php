<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string getTitle()          Product name
 * @method string getDescription()    Product description
 * @method string getStartParameter() Unique bot deep-linking parameter that can be used to generate this invoice
 * @method string getCurrency()       Three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars
 * @method int    getTotalAmount()    Total price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
 */
class Invoice extends Entity
{
    //
}
