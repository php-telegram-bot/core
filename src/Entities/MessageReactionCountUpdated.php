<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method Chat            getChat()      The chat containing the message
 * @method int             getMessageId() Unique message identifier inside the chat
 * @method int             getDate()      Date of the change in Unix time
 * @method ReactionCount[] getReactions() List of reactions that are present on the message
 */
class MessageReactionCountUpdated extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'reactions' => [ReactionCount::class],
        ];
    }
}
