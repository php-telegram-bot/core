<?php

namespace PhpTelegramBot\Core\Entities\MenuButton;

use PhpTelegramBot\Core\Contracts\Factory;
use PhpTelegramBot\Core\Entities\Entity;

/**
 * @method string getType() Type of the button
 */
class MenuButton extends Entity implements Factory
{
    public const TYPE_COMMANDS = 'commands';

    public const TYPE_WEB_APP = 'web_app';

    public const TYPE_DEFAULT = 'default';

    public static function make(array $data): static
    {
        return match ($data['type']) {
            self::TYPE_COMMANDS => new MenuButtonCommands($data),
            self::TYPE_WEB_APP  => new MenuButtonWebApp($data),
            self::TYPE_DEFAULT  => new MenuButtonDefault($data),
        };
    }
}
