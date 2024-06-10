<?php

namespace PhpTelegramBot\Core\Entities\InputMedia;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\InputFile;
use PhpTelegramBot\Core\Entities\MessageEntity;

/**
 * @method string               getMedia()                 File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data under <file_attach_name> name.
 * @method string|InputFile     getThumbnail()             Optional. Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>.
 * @method string|null          getCaption()               Optional. Caption of the animation to be sent, 0-1024 characters after entities parsing
 * @method string|null          getParseMode()             Optional. Mode for parsing entities in the animation caption. See formatting options for more details.
 * @method MessageEntity[]|null getCaptionEntites()        Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 * @method bool|null            getShowCaptionAboveMedia() Optional. Pass True, if the caption must be shown above the message media
 * @method int|null             getWidth()                 Optional. Animation width
 * @method int|null             getHeight()                Optional. Animation height
 * @method int|null             getDuration()              Optional. Animation duration in seconds
 * @method bool                 hasSpoiler()               Optional. Pass True if the animation needs to be covered with a spoiler animation
 */
class InputMediaAnimation extends InputMedia implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'caption_entities' => [MessageEntity::class],
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'has_spoiler' => false,
        ];
    }
}
