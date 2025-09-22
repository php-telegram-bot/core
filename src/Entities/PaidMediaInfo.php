<?php

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Entities\PaidMedia\PaidMedia;

/**
 * Class PaidMediaInfo
 *
 * Describes the paid media.
 *
 * @link https://core.telegram.org/bots/api#paidmediainfo
 *
 * @method int getStarCount() The number of Telegram Stars that must be paid to buy access to the media
 * @method PaidMedia[] getPaidMedia() The paid media
 *
 * @method $this setStarCount(int $starCount) The number of Telegram Stars that must be paid to buy access to the media
 * @method $this setPaidMedia(PaidMedia[] $paidMedia) The paid media
 */
class PaidMediaInfo extends Entity
{
    protected function subEntities(): array
    {
        return [
            'paid_media' => [PaidMedia::class],
        ];
    }
}
