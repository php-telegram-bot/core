<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string         getFileId()       Identifier for this file, which can be used to download or reuse the file
 * @method string         getFileUniqueId() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method int            getLength()       Video width and height (diameter of the video message), as defined by sender
 * @method int            getDuration()     Duration of the video in seconds as defined by sender
 * @method PhotoSize|null getThumbnail()    Optional. Video thumbnail
 * @method int|null       getFileSize()     Optional. File size in bytes
 */
class VideoNote extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'thumbnail' => PhotoSize::class,
        ];
    }
}
