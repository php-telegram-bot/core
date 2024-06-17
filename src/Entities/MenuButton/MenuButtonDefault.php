<?php

namespace PhpTelegramBot\Core\Entities\MenuButton;

class MenuButtonDefault extends MenuButton
{
    protected static function presetData(): array
    {
        return [
            'type' => self::TYPE_DEFAULT,
        ];
    }
}
