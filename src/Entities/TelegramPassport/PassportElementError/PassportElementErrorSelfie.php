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
 * Class PassportElementErrorSelfie
 *
 * Represents an issue with the selfie with a document. The error is considered resolved when the file with the selfie changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorselfie
 *
 * @method string getSource()   Error source, must be selfie
 * @method string getType()     The section of the user's Telegram Passport which has the issue, one of “passport”, “driver_license”, “identity_card”, “internal_passport”
 * @method string getFileHash() Base64-encoded hash of the file with the selfie
 * @method string getMessage()  Error message
 */
class PassportElementErrorSelfie extends Entity implements PassportElementError
{
    /**
     * PassportElementErrorSelfie constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['source'] = 'selfie';
        parent::__construct($data);
    }
}
