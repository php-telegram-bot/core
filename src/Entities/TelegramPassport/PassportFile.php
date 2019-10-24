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
 * Class PassportFile
 *
 * This object represents a file uploaded to Telegram Passport. Currently all Telegram Passport files are in JPEG format when decrypted and don't exceed 10MB.
 *
 * @link https://core.telegram.org/bots/api#passportfile
 *
 * @method string getFileId()   Unique identifier for this file
 * @method int    getFileSize() File size
 * @method int    getFileDate() Unix time when the file was uploaded
 **/
class PassportFile extends Entity
{

}
