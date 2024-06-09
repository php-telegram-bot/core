<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method Chat getChat() Chat that posted the story
 * @method int  getId()   Unique identifier for the story in the chat
 */
class Story extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
        ];
    }
}
