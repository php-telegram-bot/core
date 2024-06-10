<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string              getType()        Element type. One of “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport”, “address”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration”, “temporary_registration”, “phone_number”, “email”.
 * @method string|null         getData()        Optional. Base64-encoded encrypted Telegram Passport element data provided by the user; available only for “personal_details”, “passport”, “driver_license”, “identity_card”, “internal_passport” and “address” types. Can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method string|null         getPhoneNumber() Optional. User's verified phone number; available only for “phone_number” type
 * @method string|null         getEmail()       Optional. User's verified email address; available only for “email” type
 * @method PassportFile[]|null getFiles()       Optional. Array of encrypted files with documents provided by the user; available only for “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method PassportFile|null   getFrontSide()   Optional. Encrypted file with the front side of the document, provided by the user; available only for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method PassportFile|null   getReverseSide() Optional. Encrypted file with the reverse side of the document, provided by the user; available only for “driver_license” and “identity_card”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method PassportFile|null   getSelfie()      Optional. Encrypted file with the selfie of the user holding a document, provided by the user; available if requested for “passport”, “driver_license”, “identity_card” and “internal_passport”. The file can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method PassportFile[]|null getTranslation() Optional. Array of encrypted files with translated versions of documents provided by the user; available if requested for “passport”, “driver_license”, “identity_card”, “internal_passport”, “utility_bill”, “bank_statement”, “rental_agreement”, “passport_registration” and “temporary_registration” types. Files can be decrypted and verified using the accompanying EncryptedCredentials.
 * @method string              getHash()        Base64-encoded element hash for using in PassportElementErrorUnspecified
 */
class EncryptedPassportElement extends Entity
{
    public const TYPE_PERSONAL_DETAILS = 'personal_details';

    public const TYPE_PASSPORT = 'passport';

    public const TYPE_DRIVER_LICENSE = 'driver_license';

    public const TYPE_IDENTITY_CARD = 'identity_card';

    public const TYPE_INTERNAL_PASSPORT = 'internal_passport';

    public const TYPE_ADDRESS = 'address';

    public const TYPE_UTILITY_BILL = 'utility_bill';

    public const TYPE_BANK_STATEMENT = 'bank_statement';

    public const TYPE_RENTAL_AGREEMENT = 'rental_agreement';

    public const TYPE_PASSPORT_REGISTRATION = 'passport_registration';

    public const TYPE_TEMPORARY_REGISTRATION = 'temporary_registration';

    public const TYPE_PHONE_NUMBER = 'phone_number';

    public const TYPE_EMAIL = 'email';

    protected static function subEntities(): array
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
