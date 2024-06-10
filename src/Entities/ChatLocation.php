<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method Location getLocation() The location to which the supergroup is connected. Can't be a live location.
 * @method string   getAddress()  Location address; 1-64 characters, as defined by the chat owner
 */
class ChatLocation extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'location' => Location::class,
        ];
    }
}
