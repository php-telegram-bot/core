<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class InputStoryContentVideo
 *
 * Describes a video to post as a story.
 *
 * @link https://core.telegram.org/bots/api#inputstorycontentvideo
 *
 * @method string getType()                Type of the content, must be video
 * @method string getVideo()               The video to post as a story. The video must be of the size 720x1280, streamable, encoded with H.265 codec, with key frames added each second in the MPEG4 format, and must not exceed 30 MB. The video can't be reused and can only be uploaded as a new file, so you can pass “attach://<file_attach_name>” if the video was uploaded using multipart/form-data under <file_attach_name>.
 * @method float  getDuration()            Optional. Precise duration of the video in seconds; 0-60
 * @method float  getCoverFrameTimestamp() Optional. Timestamp in seconds of the frame that will be used as the static cover for the story. Defaults to 0.0.
 * @method bool   getIsAnimation()         Optional. Pass True if the video has no sound
 */
class InputStoryContentVideo extends InputStoryContent
{
}
