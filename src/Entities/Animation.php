<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string         getFileId()       Identifier for this file, which can be used to download or reuse the file
 * @method string         getFileUniqueId() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method int            getWidth()        Video width as defined by sender
 * @method int            getHeight()       Video height as defined by sender
 * @method int            getDuration()     Duration of the video in seconds as defined by sender
 * @method PhotoSize|null getThumbnail()    Optional. Animation thumbnail as defined by sender
 * @method string|null    getFileName()     Optional. Original animation filename as defined by sender
 * @method string|null    getMimeType()     Optional. MIME type of the file as defined by sender
 * @method int|null       getFileSize()     Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
 */
class Animation extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'thumbnail' => PhotoSize::class,
        ];
    }
}
