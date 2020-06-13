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
 * Class Invoice
 *
 * This object contains basic information about an invoice.
 *
 * @link https://core.telegram.org/bots/api#invoice
 *
 * @method string getTitle()          Product name
 * @method string getDescription()    Product description
 * @method string getStartParameter() Unique bot deep-linking parameter that can be used to generate this invoice
 * @method string getCurrency()       Three-letter ISO 4217 currency code
 * @method int    getTotalAmount()    Total price in the smallest units of the currency (integer, not float/double).
 **/
class Invoice extends Entity
{

}
