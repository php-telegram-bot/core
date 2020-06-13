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
 * Class EncryptedPassportElement
 *
 * Contains information about documents or other Telegram Passport elements shared with the bot by the user.
 *
 * @link https://core.telegram.org/bots/api#encryptedpassportelement
 *
 * @method string         getType()        Element type. One of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”, “phone_number”, “email”.
 * @method string         getData()        Optional. Base64-encoded encrypted Telegram Passport element data provided by the user, available for “personal_details”, “passport”, “driver_license”, “identity_card”, “identity_passport” and “address” types. Can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method string         getPhoneNumber() Optional. User's verified phone number, available only for “phone_number” type
 * @method string         getEmail()       Optional. User's verified email address, available only for “email” type
 * @method PassportFile[] getFiles()       Optional. Array of encrypted files with documents provided by the user, available for “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method PassportFile   getFrontSide()   Optional. Encrypted file with the front side of the document, provided by the user. Available for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method PassportFile   getReverseSide() Optional. Encrypted file with the reverse side of the document, provided by the user. Available for “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method PassportFile   getSelfie()      Optional. Encrypted file with the selfie of the user holding a document, provided by the user; available for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method PassportFile[] getTranslation() Optional. Array of encrypted files with translated versions of documents provided by the user. Available if requested for “passport”, “driver_license”, “identity_card”, “internal_passport”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method string         getHash()        Base64-encoded element hash for using in PassportElementErrorUnspecified
 **/
class EncryptedPassportElement extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'files'        => [PassportFile::class],
            'front_side'   => PassportFile::class,
            'reverse_side' => PassportFile::class,
            'selfie'       => PassportFile::class,
            'translation'  => [PassportFile::class],
        ];
    }
}
