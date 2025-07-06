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
 * Class Birthdate
 *
 * Represents a birthdate of a user.
 *
 * @link https://core.telegram.org/bots/api#birthdate
 *
 * @method int getDay()   Day of the month; 1-31
 * @method int getMonth() Month of the year; 1-12
 * @method int getYear()  Optional. Year of birth; 1900-2100
 */
class Birthdate extends Entity
{

}
