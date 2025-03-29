<?php

namespace Longman\TelegramBot\Entities\ChatBoostSource;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\User;

/**
 * The boost was obtained by subscribing to Telegram Premium or by gifting a Telegram Premium subscription to another user.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcepremium
 *
 * @method string getSource() Source of the boost, always “premium”
 * @method User   getUser()   User that boosted the chat
 */
class ChatBoostSourcePremium extends Entity implements ChatBoostSource
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
