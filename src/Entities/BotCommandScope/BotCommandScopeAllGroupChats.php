<?php

namespace PhpTelegramBot\Core\Entities\BotCommandScope;

class BotCommandScopeAllGroupChats extends BotCommandScope
{
    protected static function presetData(): array
    {
        return [
            'type' => self::TYPE_ALL_GROUP_CHATS,
        ];
    }
}
