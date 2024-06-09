<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string   getFileId()       Identifier for this file, which can be used to download or reuse the file
 * @method string   getFileUniqueId() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method int      getWidth()        Photo width
 * @method int      getHeight()       Photo height
 * @method int|null getFileSize()     Optional. File size in bytes
 */
class PhotoSize extends Entity
{
    //
}
