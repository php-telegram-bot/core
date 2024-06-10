<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method int                          getRequestId()               Signed 32-bit identifier of the request, which will be received back in the ChatShared object. Must be unique within the message
 * @method bool                         getChatIsChannel()           Pass True to request a channel chat, pass False to request a group or a supergroup chat.
 * @method bool|null                    getChatIsForum()             Optional. Pass True to request a forum supergroup, pass False to request a non-forum chat. If not specified, no additional restrictions are applied.
 * @method bool|null                    getChatHasUsername()         Optional. Pass True to request a supergroup or a channel with a username, pass False to request a chat without a username. If not specified, no additional restrictions are applied.
 * @method bool|null                    getChatIsCreated()           Optional. Pass True to request a chat owned by the user. Otherwise, no additional restrictions are applied.
 * @method ChatAdministratorRights|null getUserAdministratorRights() Optional. A JSON-serialized object listing the required administrator rights of the user in the chat. The rights must be a superset of bot_administrator_rights. If not specified, no additional restrictions are applied.
 * @method ChatAdministratorRights|null getBotAdministratorRights()  Optional. A JSON-serialized object listing the required administrator rights of the bot in the chat. The rights must be a subset of user_administrator_rights. If not specified, no additional restrictions are applied.
 * @method bool|null                    getBotIsMember()             Optional. Pass True to request a chat with the bot as a member. Otherwise, no additional restrictions are applied.
 * @method bool|null                    getRequestTitle()            Optional. Pass True to request the chat's title
 * @method bool|null                    getRequestUsername()         Optional. Pass True to request the chat's username
 * @method bool|null                    getRequestPhoto()            Optional. Pass True to request the chat's photo
 */
class KeyboardButtonRequestChat extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'user_administrator_rights' => ChatAdministratorRights::class,
            'bot_administrator_rights'  => ChatAdministratorRights::class,
        ];
    }
}
