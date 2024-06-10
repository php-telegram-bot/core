<?php

namespace PhpTelegramBot\Core\Entities\BackgroundType;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;
use PhpTelegramBot\Core\Entities\Document;

/**
 * @method Document getDocument()         Document with the wallpaper
 * @method int      getDarkThemeDimming() Dimming of the background in dark themes, as a percentage; 0-100
 * @method bool     isBlurred()           Optional. True, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred with radius 12
 * @method bool     isMoving()            Optional. True, if the background moves slightly when the device is tilted
 */
class BackgroundTypeWallpaper extends BackgroundType implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'document' => Document::class,
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'is_blurred' => false,
            'is_moving'  => false,
        ];
    }
}
