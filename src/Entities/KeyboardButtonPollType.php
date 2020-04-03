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
 * Class KeyboardButtonPollType
 *
 * This entity represents type of a poll, which is allowed to be created and sent when the corresponding button is pressed.
 *
 * @link https://core.telegram.org/bots/api#keyboardbutton
 *
 * @method string getType() Optional. If 'quiz' is passed, the user will be allowed to create only polls in the quiz mode. If 'regular' is passed, only regular polls will be allowed. Otherwise, the user will be allowed to create a poll of any type.
 *
 * @method $this setType(string $type) Optional. If 'quiz' is passed, the user will be allowed to create only polls in the quiz mode. If 'regular' is passed, only regular polls will be allowed. Otherwise, the user will be allowed to create a poll of any type.
 */
class KeyboardButtonPollType extends Entity
{

}
