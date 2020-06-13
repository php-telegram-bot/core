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
 * Class Contact
 *
 * @link https://core.telegram.org/bots/api#contact
 *
 * @method string getPhoneNumber() Contact's phone number
 * @method string getFirstName()   Contact's first name
 * @method string getLastName()    Optional. Contact's last name
 * @method int    getUserId()      Optional. Contact's user identifier in Telegram
 * @method string getVcard()       Optional. Additional data about the contact in the form of a vCard
 */
class Contact extends Entity
{

}
