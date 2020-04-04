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
 * Class BotCommand
 *
 * This entity represents a bot command.
 *
 * @link https://core.telegram.org/bots/api#botcommand
 *
 * @method string getCommand()     Text of the command, 1-32 characters. Can contain only lowercase English letters, digits and underscores.
 * @method string getDescription() Description of the command, 3-256 characters.
 */
class BotCommand extends Entity
{

}
