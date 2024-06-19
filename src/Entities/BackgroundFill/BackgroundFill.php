<?php

namespace PhpTelegramBot\Core\Entities\BackgroundFill;

use PhpTelegramBot\Core\Contracts\Factory;
use PhpTelegramBot\Core\Entities\Entity;

/**
 * @method string getType() Type of the background fill
 */
class BackgroundFill extends Entity implements Factory
{
    public const TYPE_SOLID = 'solid';

    public const TYPE_GRADIENT = 'gradient';

    public const TYPE_FREEFORM_GRADIENT = 'freeform_gradient';

    public static function make(array $data): static
    {
        return match ($data['type']) {
            self::TYPE_SOLID             => new BackgroundFillSolid($data),
            self::TYPE_GRADIENT          => new BackgroundFillGradient($data),
            self::TYPE_FREEFORM_GRADIENT => new BackgroundFillFreeformGradient($data),
        };
    }
}
