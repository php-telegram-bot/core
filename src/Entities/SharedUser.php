<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method int              getUserId()    Identifier of the shared user. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so 64-bit integers or double-precision float types are safe for storing these identifiers. The bot may not have access to the user and could be unable to use this identifier, unless the user is already known to the bot by some other means.
 * @method string|null      getFirstName() Optional. First name of the user, if the name was requested by the bot
 * @method string|null      getLastName()  Optional. Last name of the user, if the name was requested by the bot
 * @method string|null      getUsername()  Optional. Username of the user, if the username was requested by the bot
 * @method PhotoSize[]|null getPhoto()     Optional. Available sizes of the chat photo, if the photo was requested by the bot
 */
class SharedUser extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'photo' => [PhotoSize::class],
        ];
    }
}
