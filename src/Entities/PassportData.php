<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method EncryptedPassportElement[] getData()        Array with information about documents and other Telegram Passport elements that was shared with the bot
 * @method EncryptedCredentials       getCredentials() Encrypted credentials required to decrypt the data
 */
class PassportData extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'data' => [EncryptedPassportElement::class],
            'credentials' => EncryptedCredentials::class,
        ];
    }
}
