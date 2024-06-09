<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string getBusinessConnectionId() Unique identifier of the business connection
 * @method Chat   getChat()                 Information about a chat in the business account. The bot may not have access to the chat or the corresponding user.
 * @method int[]  getMessageIds()           The list of identifiers of deleted messages in the chat of the business account
 */
class BusinessMessagesDeleted extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chat' => Chat::class,
        ];
    }
}
