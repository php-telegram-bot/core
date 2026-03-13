<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class ChatOwnerChanged
 *
 * This object represents a service message about a chat owner change.
 *
 * @link https://core.telegram.org/bots/api#chatownerchanged
 *
 * @method Chat getChat()     Chat that the owner change happened in
 * @method User getNewOwner() New owner of the chat
 */
class ChatOwnerChanged extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'chat'      => Chat::class,
            'new_owner' => User::class,
        ];
    }
}
