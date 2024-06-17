<?php

namespace PhpTelegramBot\Core\Entities\BotCommandScope;

class BotCommandScopeAllChatAdministrators extends BotCommandScope
{
    //

    protected static function presetData(): array
    {
        return [
            'type' => self::TYPE_ALL_CHAT_ADMINISTRATORS,
        ];
    }
}
