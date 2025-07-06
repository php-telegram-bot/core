<?php

namespace Longman\TelegramBot\Entities\PaidMedia;

use Longman\TelegramBot\Entities\PhotoSize;

/**
 * Class PaidMediaPhoto
 *
 * The paid media is a photo.
 *
 * @link https://core.telegram.org/bots/api#paidmediaphoto
 *
 * @method string getType() Type of the paid media, always “photo”
 * @method PhotoSize[] getPhoto() The photo
 *
 * @method $this setType(string $type) Type of the paid media, always “photo”
 * @method $this setPhoto(PhotoSize[] $photo) The photo
 */
class PaidMediaPhoto extends PaidMedia
{
    protected function subEntities(): array
    {
        return [
            'photo' => [PhotoSize::class],
        ];
    }
}
