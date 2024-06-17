<?php

namespace PhpTelegramBot\Core\Entities\BotCommandScope;

/**
 * @method int|string getChatId() Unique identifier for the target chat or username of the target supergroup (in the format @supergroupusername).
 * @method int        getUserId() Unique identifier of the target user
 */
class BotCommandScopeChatMember extends BotCommandScope
{
    protected static function presetData(): array
    {
        return [
            'type' => self::TYPE_CHAT_MEMBER,
        ];
    }
}
