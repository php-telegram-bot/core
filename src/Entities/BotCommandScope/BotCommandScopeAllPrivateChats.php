<?php

namespace PhpTelegramBot\Core\Entities\BotCommandScope;

class BotCommandScopeAllPrivateChats extends BotCommandScope
{
    protected static function presetData(): array
    {
        return [
            'type' => self::TYPE_ALL_PRIVATE_CHATS,
        ];
    }
}
