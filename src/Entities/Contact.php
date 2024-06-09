<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string      getPhoneNumber() Contact's phone number
 * @method string      getFirstName()   Contact's first name
 * @method string|null getLastName()    Optional. Contact's last name
 * @method int|null    getUserId()      Optional. Contact's user identifier in Telegram. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method string|null getVcard()       Optional. Additional data about the contact in the form of a vCard
 */
class Contact extends Entity
{
    //
}
