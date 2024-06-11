<?php

namespace PhpTelegramBot\Core\Entities\InputMessageContent;

/**
 * @method string      getPhoneNumber() Contact's phone number
 * @method string      getFirstName()   Contact's first name
 * @method string|null getLastName()    Optional. Contact's last name
 * @method string|null getVcard()       Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
 */
class InputContactMessageContent extends InputMessageContent
{
    //
}
