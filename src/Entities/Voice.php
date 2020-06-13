<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

/**
 * Class Voice
 *
 * @link https://core.telegram.org/bots/api#voice
 *
 * @method string getFileId()       Identifier for this file, which can be used to download or reuse the file
 * @method string getFileUniqueId() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method int    getDuration()     Duration of the audio in seconds as defined by sender
 * @method string getMimeType()     Optional. MIME type of the file as defined by sender
 * @method int    getFileSize()     Optional. File size
 */
class Voice extends Entity
{

}
