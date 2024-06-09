<?php

namespace PhpTelegramBot\Core\Entities\BackgroundType;

use PhpTelegramBot\Core\Entities\BackgroundFill\BackgroundFill;
use PhpTelegramBot\Core\Entities\Document;

/**
 * @method Document       getDocument()   Document with the pattern
 * @method BackgroundFill getFill()       The background fill that is combined with the pattern
 * @method int            getIntensity()  Intensity of the pattern when it is shown above the filled background; 0-100
 * @method true|null      getIsInverted() Optional. True, if the background fill must be applied only to the pattern itself. All other pixels are black in this case. For dark themes only
 * @method true|null      getIsMoving()   Optional. True, if the background moves slightly when the device is tilted
 */
class BackgroundTypePattern extends BackgroundType
{
    protected static function subEntities(): array
    {
        return [
            'document' => Document::class,
            'fill' => BackgroundFill::class,
        ];
    }
}
