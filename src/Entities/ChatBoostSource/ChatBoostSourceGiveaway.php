<?php

namespace Longman\TelegramBot\Entities\ChatBoostSource;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\User;

/**
 * The boost was obtained by the creation of a Telegram Premium giveaway. This boosts the chat 4 times for the duration of the corresponding Telegram Premium subscription.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcegiveaway
 *
 * @method string getSource()            Source of the boost, always “giveaway”
 * @method int    getGiveawayMessageId() Identifier of a message in the chat with the giveaway; the message could have been deleted already. May be 0 if the message isn't sent yet.
 * @method User   getUser()              Optional. User that won the prize in the giveaway if any
 * @method bool   getIsUnclaimed()       Optional. True, if the giveaway was completed, but there was no user to win the prize
 */
class ChatBoostSourceGiveaway extends Entity implements ChatBoostSource
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'user' => User::class,
        ];
    }
}
