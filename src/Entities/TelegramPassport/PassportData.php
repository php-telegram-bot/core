<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\TelegramPassport;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class PassportData
 *
 * Contains information about Telegram Passport data shared with the bot by the user.
 *
 * @link https://core.telegram.org/bots/api#passportdata
 *
 * @method EncryptedPassportElement[] getData()        Array with information about documents and other Telegram Passport elements that was shared with the bot
 * @method EncryptedCredentials       getCredentials() Encrypted credentials required to decrypt the data
 **/
class PassportData extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'data'        => [EncryptedPassportElement::class],
            'credentials' => EncryptedCredentials::class,
        ];
    }
}
