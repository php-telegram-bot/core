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
 * Class User
 *
 * @link https://core.telegram.org/bots/api#user
 *
 * @method int    getId()           Unique identifier for this user or bot
 * @method bool   getIsBot()        True, if this user is a bot
 * @method string getFirstName()    User's or bot’s first name
 * @method string getLastName()     Optional. User's or bot’s last name
 * @method string getUsername()     Optional. User's or bot’s username
 * @method string getLanguageCode() Optional. User's system language
 */
class User extends Entity
{

}
