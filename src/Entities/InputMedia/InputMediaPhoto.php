<?php

namespace PhpTelegramBot\Core\Entities\InputMedia;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\MessageEntity;

/**
 * @method string               getMedia()                 File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files »
 * @method string|null          getCaption()               Optional. Caption of the photo to be sent, 0-1024 characters after entities parsing
 * @method string|null          getParseMode()             Optional. Mode for parsing entities in the photo caption. See formatting options for more details.
 * @method MessageEntity[]|null getCaptionEntities()       Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 * @method bool|null            getShowCaptionAboveMedia() Optional. Pass True, if the caption must be shown above the message media
 * @method bool                 hasSpoiler()               Optional. Pass True if the photo needs to be covered with a spoiler animation
 */
class InputMediaPhoto extends InputMedia implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'caption_entities' => [MessageEntity::class],
        ];
    }

    protected static function presetData(): array
    {
        return [
            'type' => self::TYPE_PHOTO,
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'has_spoiler' => false,
        ];
    }
}
