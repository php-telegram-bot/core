<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class InputStoryContentPhoto
 *
 * Describes a photo to post as a story.
 *
 * @link https://core.telegram.org/bots/api#inputstorycontentphoto
 *
 * @method string getType()  Type of the content, must be photo
 * @method string getPhoto() The photo to post as a story. The photo must be of the size 1080x1920 and must not exceed 10 MB. The photo can't be reused and can only be uploaded as a new file, so you can pass “attach://<file_attach_name>” if the photo was uploaded using multipart/form-data under <file_attach_name>.
 */
class InputStoryContentPhoto extends InputStoryContent
{
}
