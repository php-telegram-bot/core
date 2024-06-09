<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method Chat getChat()      Chat the message belonged to
 * @method int  getMessageId() Unique message identifier inside the chat
 * @method int  getDate()      The field can be used to differentiate regular and inaccessible messages.
 */
class MaybeInaccessibleMessage extends Entity implements Factory
{
    public static function make(array $data): static
    {
        return match (true) {
            $data['date'] === 0 => new InaccessibleMessage($data),
            default => new Message($data),
        };
    }

    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
        ];
    }
}
