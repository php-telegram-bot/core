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
 * Class Dice
 *
 * This entity represents a dice with random value from 1 to 6.
 *
 * @link https://core.telegram.org/bots/api#dice
 *
 * @method string getEmoji() Emoji on which the dice throw animation is based
 * @method int    getValue() Value of the dice, 1-6 for “🎲” and “🎯” base emoji, 1-5 for “🏀” and “⚽” base emoji, 1-64 for “🎰” base emoji
 */
class Dice extends Entity
{

}
