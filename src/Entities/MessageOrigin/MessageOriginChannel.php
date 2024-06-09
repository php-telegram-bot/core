<?php

namespace PhpTelegramBot\Core\Entities\MessageOrigin;

use PhpTelegramBot\Core\Entities\Chat;

/**
 * @method Chat        getChat()            Channel chat to which the message was originally sent
 * @method int         getMessageId()       Unique message identifier inside the chat
 * @method string|null getAuthorSignature() Optional. Signature of the original post author
 */
class MessageOriginChannel extends MessageOrigin
{
    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
        ];
    }
}
