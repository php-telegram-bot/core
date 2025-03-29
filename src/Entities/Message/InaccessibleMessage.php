<?php

namespace Longman\TelegramBot\Entities\Message;

use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\Entity;

/**
 * @method Chat getChat()      Chat the message belonged to
 * @method int  getMessageId() Unique message identifier inside the chat
 * @method int  getDate()      Always 0. The field can be used to differentiate regular and inaccessible messages.
 */
class InaccessibleMessage extends Entity implements MaybeInaccessibleMessage
{
    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
        ];
    }
}
