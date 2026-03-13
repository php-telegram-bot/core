<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class GiftInfo
 *
 * Describes a service message about a regular gift that was sent or received.
 *
 * @link https://core.telegram.org/bots/api#giftinfo
 *
 * @method Gift            getGift()                       Information about the gift
 * @method string          getOwnedGiftId()                Optional. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
 * @method int             getConvertStarCount()           Optional. Number of Telegram Stars that can be claimed by the receiver by converting the gift; omitted if conversion to Telegram Stars is impossible
 * @method int             getPrepaidUpgradeStarCount()    Optional. Number of Telegram Stars that were prepaid for the ability to upgrade the gift
 * @method bool            getIsUpgradeSeparate()          Optional. True, if the gift's upgrade was purchased after the gift was sent
 * @method bool            getCanBeUpgraded()              Optional. True, if the gift can be upgraded to a unique gift
 * @method string          getText()                       Optional. Text of the message that was added to the gift
 * @method MessageEntity[] getEntities()                   Optional. Special entities that appear in the text
 * @method bool            getIsPrivate()                  Optional. True, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method int             getUniqueGiftNumber()           Optional. Unique number reserved for this gift when upgraded
 */
class GiftInfo extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'gift'     => Gift::class,
            'entities' => [MessageEntity::class],
        ];
    }
}
