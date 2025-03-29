<?php

namespace Longman\TelegramBot\Entities\MessageOrigin;

use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\Entity;

/**
 * The message was originally sent to a channel chat.
 *
 * @link https://core.telegram.org/bots/api#messageoriginchannel
 *
 * @method string getType()            Type of the message origin, always “channel”
 * @method int    getDate()            Date the message was sent originally in Unix time
 * @method Chat   getChat()            Channel chat to which the message was originally sent
 * @method int    getMessageId()       Unique message identifier inside the chat
 * @method string getAuthorSignature() Optional. Signature of the original post author
 */
class MessageOriginChannel extends Entity implements MessageOrigin
{
    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
        ];
    }
}
