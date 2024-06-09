<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string getData()   Base64-encoded encrypted JSON-serialized data with unique user's payload, data hashes and secrets required for EncryptedPassportElement decryption and authentication
 * @method string getHash()   Base64-encoded data hash for data authentication
 * @method string getSecret() Base64-encoded secret, encrypted with the bot's public RSA key, required for data decryption
 */
class EncryptedCredentials extends Entity
{
    //
}
