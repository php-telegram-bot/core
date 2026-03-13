<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class VideoQuality
 *
 * This object describes the quality of a video.
 *
 * @link https://core.telegram.org/bots/api#videoquality
 *
 * @method string getFileId()       Identifier for this file, which can be used to download or reuse the file
 * @method string getFileUniqueId() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method int    getWidth()        Video width
 * @method int    getHeight()       Video height
 * @method string getCodec()        Codec that was used to encode the video, for example, “h264”, “h265”, or “av01”
 * @method int    getFileSize()     Optional. File size in bytes
 */
class VideoQuality extends Entity
{
}
