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
 * @property int    $id         Unique identifier for this user or bot
 * @property string $first_name User's or bot’s first name
 * @property string $last_name  Optional. User's or bot’s last name
 * @property string $username   Optional. User's or bot’s username
 *
 * @method int    getId()        Unique identifier for this user or bot
 * @method string getFirstName() User's or bot’s first name
 * @method string getLastName()  Optional. User's or bot’s last name
 * @method string getUsername()  Optional. User's or bot’s username
 */
class User extends Entity
{

}
