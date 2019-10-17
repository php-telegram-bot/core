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
 * Class File
 *
 * @link https://core.telegram.org/bots/api#file
 *
 * @method string getFileId()   Unique identifier for this file
 * @method int    getFileSize() Optional. File size, if known
 * @method string getFilePath() Optional. File path. Use https://api.telegram.org/file/bot<token>/<file_path> to get the file.
 */
class File extends Entity
{

}
