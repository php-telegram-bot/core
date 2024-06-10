<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string        getId()       Unique identifier for this query
 * @method User          getFrom()     Sender
 * @method string        getQuery()    Text of the query (up to 256 characters).
 * @method string        getOffset()   Offset of the results to be returned, can be controlled by the bot
 * @method string|null   getChatType() Optional. Type of the chat from which the inline query was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”. The chat type should be always known for requests sent from official clients and most third-party clients, unless the request was sent from a secret chat
 * @method Location|null getLocation() Optional. Sender location, only for bots that request user location
 */
class InlineQuery extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'from'     => User::class,
            'location' => Location::class,
        ];
    }
}
