<?php

namespace Longman\TelegramBot\Entities\ChatBoostSource;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\User;

/**
 * The boost was obtained by the creation of Telegram Premium gift codes to boost a chat.
 *
 * @link https://core.telegram.org/bots/api#chatboostsourcegiftcode
 *
 * @method string getSource() Source of the boost, always “gift_code”
 * @method User   getUser()   User for which the gift code was created
 */
class ChatBoostSourceGiftCode extends Entity implements ChatBoostSource
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
