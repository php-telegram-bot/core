<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class OwnedGiftUnique
 *
 * Describes a unique gift received and owned by a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#ownedgiftunique
 *
 * @method string     getType()               Type of the gift, always “unique”
 * @method UniqueGift getGift()               Information about the unique gift
 * @method string     getOwnedGiftId()        Optional. Unique identifier of the received gift for the bot; for gifts received on behalf of business accounts only
 * @method User       getSenderUser()         Optional. Sender of the gift if it is a known user
 * @method int        getSendDate()           Date the gift was sent in Unix time
 * @method bool       getIsSaved()            Optional. True, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
 * @method bool       getCanBeTransferred()   Optional. True, if the gift can be transferred to another owner; for gifts received on behalf of business accounts only
 * @method int        getTransferStarCount()  Optional. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
 * @method int        getNextTransferDate()   Optional. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now
 */
class OwnedGiftUnique extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'gift'        => UniqueGift::class,
            'sender_user' => User::class,
        ];
    }
}
