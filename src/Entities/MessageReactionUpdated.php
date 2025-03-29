<?php

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Entities\ReactionType\Factory as ReactionTypeFactory;
use Longman\TelegramBot\Entities\ReactionType\ReactionType;

/**
 * This object represents a change of a reaction on a message performed by a user.
 *
 * @link https://core.telegram.org/bots/api#messagereactionupdated
 *
 * @method Chat           getChat()        The chat containing the message the user reacted to
 * @method int            getMessageId()   Unique identifier of the message inside the chat
 * @method User           getUser()        Optional. The user that changed the reaction, if the user isn't anonymous
 * @method Chat           getActorChat()   Optional. The chat on behalf of which the reaction was changed, if the user is anonymous
 * @method int            getDate()        Date of the change in Unix time
 * @method ReactionType[] getOldReaction() Previous list of reaction types that were set by the user
 * @method ReactionType[] getNewReaction() New list of reaction types that have been set by the user
 */
class MessageReactionUpdated extends Entity
{
    protected function subEntities(): array
    {
        return [
            'chat'         => Chat::class,
            'user'         => User::class,
            'actor_chat'   => Chat::class,
            'old_reaction' => [ReactionTypeFactory::class],
            'new_reaction' => [ReactionTypeFactory::class],
        ];
    }
}
