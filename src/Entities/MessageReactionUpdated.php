<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Entities\ReactionType\ReactionType;

/**
 * @method Chat           getChat()        The chat containing the message the user reacted to
 * @method int            getMessageId()   Unique identifier of the message inside the chat
 * @method User|null      getUser()        Optional. The user that changed the reaction, if the user isn't anonymous
 * @method Chat|null      getActorChat()   Optional. The chat on behalf of which the reaction was changed, if the user is anonymous
 * @method int            getDate()        Date of the change in Unix time
 * @method ReactionType[] getOldReaction() Previous list of reaction types that were set by the user
 * @method ReactionType[] getNewReaction() New list of reaction types that have been set by the user
 */
class MessageReactionUpdated extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chat'         => Chat::class,
            'user'         => User::class,
            'actor_chat'   => Chat::class,
            'old_reaction' => [ReactionType::class],
            'new_reaction' => [ReactionType::class],
        ];
    }
}
