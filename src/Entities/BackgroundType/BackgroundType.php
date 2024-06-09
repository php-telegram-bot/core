<?php

namespace PhpTelegramBot\Core\Entities\BackgroundType;

use PhpTelegramBot\Core\Entities\Entity;
use PhpTelegramBot\Core\Entities\Factory;

/**
 * @method string getType() Type of the background
 */
abstract class BackgroundType extends Entity implements Factory
{
    public const TYPE_FILL = 'fill';

    public const TYPE_WALLPAPER = 'wallpaper';

    public const TYPE_PATTERN = 'pattern';

    public const TYPE_CHAT_THEME = 'chat_theme';

    public static function make(array $data): static
    {
        return match ($data['type']) {
            self::TYPE_FILL => new BackgroundTypeFill($data),
            self::TYPE_WALLPAPER => new BackgroundTypeWallpaper($data),
            self::TYPE_PATTERN => new BackgroundTypePattern($data),
            self::TYPE_CHAT_THEME => new BackgroundTypeChatTheme($data),
        };
    }
}
