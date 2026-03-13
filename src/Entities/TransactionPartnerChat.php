<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class TransactionPartnerChat
 *
 * Describes a transaction with a chat.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnerchat
 *
 * @method string getType() Type of the transaction partner, always “chat”
 * @method Chat   getChat() Information about the chat
 * @method Gift   getGift() Optional. The gift sent to the chat by the bot
 */
class TransactionPartnerChat extends Entity implements TransactionPartner
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
            'gift' => Gift::class,
        ];
    }
}
