<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string getFileId()       Identifier for this file, which can be used to download or reuse the file
 * @method string getFileUniqueId() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method int    getFileSize()     File size in bytes
 * @method int    getFileDate()     Unix time when the file was uploaded
 */
class PassportFile extends Entity
{
    //
}
