<?php

namespace PhpTelegramBot\Core\Entities\MessageOrigin;

use PhpTelegramBot\Core\Entities\Chat;

/**
 * @method Chat        getSenderChat()      Chat that sent the message originally
 * @method string|null getAuthorSignature() Optional. For messages originally sent by an anonymous chat administrator, original message author signature
 */
class MessageOriginChat extends MessageOrigin
{
    protected static function subEntities(): array
    {
        return [
            'sender_chat' => Chat::class,
        ];
    }
}
