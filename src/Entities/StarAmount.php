<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StarAmount
 *
 * Describes an amount of Telegram Stars.
 *
 * @link https://core.telegram.org/bots/api#staramount
 *
 * @method int getAmount()         Integer amount of Telegram Stars, rounded to 0; can be negative
 * @method int getNanostarAmount() Optional. The number of 1/1000000000 shares of Telegram Stars; from -999999999 to 999999999; can be negative if and only if amount is non-positive
 */
class StarAmount extends Entity
{
}
