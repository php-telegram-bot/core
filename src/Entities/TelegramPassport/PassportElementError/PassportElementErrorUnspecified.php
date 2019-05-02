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
 * Class PassportElementErrorUnspecified
 *
 * Represents an issue in an unspecified place. The error is considered resolved when new data is added.
 *
 * @link https://core.telegram.org/bots/api#passportelementerrorunspecified
 *
 * @method string getSource()      Error source, must be unspecified
 * @method string getType()        Type of element of the user's Telegram Passport which has the issue
 * @method string getElementHash() Base64-encoded element hash
 * @method string getMessage()     Error message
 */
class PassportElementErrorUnspecified extends Entity implements PassportElementError
{
    /**
     * PassportElementErrorUnspecified constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['source'] = 'unspecified';
        parent::__construct($data);
    }
}
