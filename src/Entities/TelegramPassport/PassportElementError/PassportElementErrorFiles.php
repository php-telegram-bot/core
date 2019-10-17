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
 * Class PassportElementErrorFiles
 *
 * Represents an issue with a list of scans. The error is considered resolved when the list of files containing the scans changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorfiles
 *
 * @method string   getSource()     Error source, must be files
 * @method string   getType()       The section of the user's Telegram Passport which has the issue, one of “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”
 * @method string[] getFileHashes() List of base64-encoded file hashes
 * @method string   getMessage()    Error message
 */
class PassportElementErrorFiles extends Entity implements PassportElementError
{
    /**
     * PassportElementErrorFiles constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['source'] = 'files';
        parent::__construct($data);
    }
}
