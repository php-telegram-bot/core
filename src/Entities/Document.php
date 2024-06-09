<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string         getFileId()       Identifier for this file, which can be used to download or reuse the file
 * @method string         getFileUniqueId() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method PhotoSize|null getThumbnail()    Optional. Document thumbnail as defined by sender
 * @method string|null    getFileName()     Optional. Original filename as defined by sender
 * @method string|null    getMimeType()     Optional. MIME type of the file as defined by sender
 * @method int|null       getFileSize()     Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
 */
class Document extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'thumbnail' => PhotoSize::class,
        ];
    }
}
