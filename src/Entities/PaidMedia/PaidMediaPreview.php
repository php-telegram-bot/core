<?php

namespace Longman\TelegramBot\Entities\PaidMedia;

use Longman\TelegramBot\Entities\PhotoSize;

/**
 * Class PaidMediaPreview
 *
 * The paid media is a preview of a media album.
 *
 * @link https://core.telegram.org/bots/api#paidmediapreview
 *
 * @method string getType() Type of the paid media, always “preview”
 * @method int getWidth() Optional. Media width as defined by the sender
 * @method int getHeight() Optional. Media height as defined by the sender
 * @method int getDuration() Optional. Duration of the media in seconds as defined by the sender
 *
 * @method $this setType(string $type) Type of the paid media, always “preview”
 * @method $this setWidth(int $width) Optional. Media width as defined by the sender
 * @method $this setHeight(int $height) Optional. Media height as defined by the sender
 * @method $this setDuration(int $duration) Optional. Duration of the media in seconds as defined by the sender
 */
class PaidMediaPreview extends PaidMedia
{
    protected function subEntities(): array
    {
        return [];
    }
}
