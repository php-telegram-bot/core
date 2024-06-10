<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method Chat      getChat()  Chat which was boosted
 * @method ChatBoost getBoost() Information about the chat boost
 */
class ChatBoostUpdated extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chat'  => Chat::class,
            'boost' => ChatBoost::class,
        ];
    }
}
