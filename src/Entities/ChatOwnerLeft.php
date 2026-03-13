<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class ChatOwnerLeft
 *
 * This object represents a service message about a chat owner that left the chat.
 *
 * @link https://core.telegram.org/bots/api#chatownerleft
 *
 * @method Chat getChat()     Chat that the owner change happened in
 * @method User getNewOwner() Optional. The user which will be the new owner of the chat if the previous owner does not return to the chat
 */
class ChatOwnerLeft extends Entity
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
