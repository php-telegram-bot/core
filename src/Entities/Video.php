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
 * Class Video
 *
 * @link https://core.telegram.org/bots/api#video
 *
 * @method string         getFileId()         Identifier for this file, which can be used to download or reuse the file
 * @method string         getFileUniqueId()   Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method int            getWidth()          Video width as defined by sender
 * @method int            getHeight()         Video height as defined by sender
 * @method int            getDuration()       Duration of the video in seconds as defined by sender
 * @method PhotoSize[]    getCover()          Optional. Available sizes of the cover of the video in the message
 * @method int            getStartTimestamp() Optional. Timestamp in seconds from which the video will play in the message
 * @method PhotoSize      getThumbnail()      Optional. Video thumbnail
 * @method string         getFileName()       Optional. Original filename as defined by sender
 * @method string         getMimeType()       Optional. Mime type of a file as defined by sender
 * @method int            getFileSize()       Optional. File size
 * @method VideoQuality[] getQualities()      Optional. Available qualities of the video
 */
class Video extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'cover'     => [PhotoSize::class],
            'thumbnail' => PhotoSize::class,
            'qualities' => [VideoQuality::class],
        ];
    }
}
