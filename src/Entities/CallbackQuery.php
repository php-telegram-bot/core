<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string                        getId()              Unique identifier for this query
 * @method User                          getFrom()            Sender
 * @method MaybeInaccessibleMessage|null getMessage()         Optional. Message sent by the bot with the callback button that originated the query
 * @method string|null                   getInlineMessageId() Optional. Identifier of the message sent via the bot in inline mode, that originated the query.
 * @method string                        getChatInstance()    Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent. Useful for high scores in games.
 * @method string|null                   getData()            Optional. Data associated with the callback button. Be aware that the message originated the query can contain no callback buttons with this data.
 * @method string|null                   getGameShortName()   Optional. Short name of a Game to be returned, serves as the unique identifier for the game
 */
class CallbackQuery extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'from' => User::class,
            'message' => MaybeInaccessibleMessage::class,
        ];
    }
}
