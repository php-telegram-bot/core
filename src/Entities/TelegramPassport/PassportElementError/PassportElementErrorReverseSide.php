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
 * Class PassportElementErrorReverseSide
 *
 * Represents an issue with the reverse side of a document. The error is considered resolved when the file with reverse side of the document changes.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorreverseside
 *
 * @method string getSource()   Error source, must be reverse_side
 * @method string getType()     The section of the user's Telegram Passport which has the issue, one of “driver_license”, “identity_card”
 * @method string getFileHash() Base64-encoded hash of the file with the reverse side of the document
 * @method string getMessage()  Error message
 */
class PassportElementErrorReverseSide extends Entity implements PassportElementError
{
    /**
     * PassportElementErrorReverseSide constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['source'] = 'reverse_side';
        parent::__construct($data);
    }
}
