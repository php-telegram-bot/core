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
 * Class EncryptedCredentials
 *
 * Contains data required for decrypting and authenticating EncryptedCredentials. See the Telegram Passport Documentation for a complete description of the data decryption and authentication processes.
 *
 * @link https://core.telegram.org/bots/api#encryptedcredentials
 *
 * @method string getData()   Base64-encoded encrypted JSON-serialized data with unique user's payload, data hashes and secrets required for EncryptedPassportElement decryption and authentication
 * @method string getHash()   Base64-encoded data hash for data authentication
 * @method string getSecret() Base64-encoded secret, encrypted with the bot's public RSA key, required for data decryption
 **/
class EncryptedCredentials extends Entity
{

}
