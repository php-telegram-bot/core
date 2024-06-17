<?php

namespace PhpTelegramBot\Core\Entities\BotCommandScope;

class BotCommandScopeDefault extends BotCommandScope
{
    protected static function presetData(): array
    {
        return [
            'type' => self::TYPE_DEFAULT,
        ];
    }
}
