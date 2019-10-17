<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entities\TelegramPassport\PassportElementError;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class PassportElementErrorDataField
 *
 * Represents an issue in one of the data fields that was provided by the user. The error is considered resolved when the field's value changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrordatafield
 *
 * @method string getSource()    Error source, must be data
 * @method string getType()      The section of the user's Telegram Passport which has the error, one of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”
 * @method string getFieldName() Name of the data field which has the error
 * @method string getDataHash()  Base64-encoded data hash
 * @method string getMessage()   Error message
 */
class PassportElementErrorDataField extends Entity implements PassportElementError
{
    /**
     * PassportElementErrorDataField constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['source'] = 'data';
        parent::__construct($data);
    }
}
