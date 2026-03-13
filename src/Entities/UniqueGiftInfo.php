<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UniqueGiftInfo
 *
 * Describes a service message about a unique gift that was sent or received.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftinfo
 *
 * @method UniqueGift getGift()                  Information about the gift
 * @method string     getOrigin()                Origin of the gift. Currently, either “upgrade” for gifts upgraded from regular gifts, “transfer” for gifts transferred from other users or channels, “resale” for gifts bought from other users, “gifted_upgrade” for upgrades purchased after the gift was sent, or “offer” for gifts bought or sold through gift purchase offers
 * @method string     getLastResaleCurrency()    Optional. For gifts bought from other users, the currency in which the payment for the gift was done. Currently, one of “XTR” for Telegram Stars or “TON” for toncoins.
 * @method int        getLastResaleAmount()      Optional. For gifts bought from other users, the price paid for the gift in either Telegram Stars or nanotoncoins
 * @method string     getOwnedGiftId()           Optional. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
 * @method int        getTransferStarCount()     Optional. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
 * @method int        getNextTransferDate()      Optional. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now
 */
class UniqueGiftInfo extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'gift' => UniqueGift::class,
        ];
    }
}
