<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method int     getTotalCount() Total number of profile pictures the target user has
 * @method array[] getPhotos()     Requested profile pictures (in up to 4 sizes each).
 */
class UserProfilePhotos extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'photos' => [[PhotoSize::class]],
        ];
    }
}
