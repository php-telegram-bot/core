<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class OwnedGiftRegular
 *
 * Describes a regular gift owned by a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#ownedgiftregular
 *
 * @method string        getType()                       Type of the gift, always “regular”
 * @method Gift          getGift()                       Information about the regular gift
 * @method string        getOwnedGiftId()                Optional. Unique identifier of the gift for the bot; for gifts received on behalf of business accounts only
 * @method User          getSenderUser()                 Optional. Sender of the gift if it is a known user
 * @method int           getSendDate()                   Date the gift was sent in Unix time
 * @method string        getText()                       Optional. Text of the message that was added to the gift
 * @method MessageEntity[] getEntities()                 Optional. Special entities that appear in the text
 * @method bool          getIsPrivate()                  Optional. True, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method bool          getIsSaved()                    Optional. True, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
 * @method bool          getCanBeUpgraded()              Optional. True, if the gift can be upgraded to a unique gift; for gifts received on behalf of business accounts only
 * @method bool          getWasRefunded()                Optional. True, if the gift was refunded and isn't available anymore
 * @method int           getConvertStarCount()           Optional. Number of Telegram Stars that can be claimed by the receiver instead of the gift; omitted if the gift cannot be converted to Telegram Stars; for gifts received on behalf of business accounts only
 * @method int           getPrepaidUpgradeStarCount()    Optional. Number of Telegram Stars that were paid for the ability to upgrade the gift
 * @method bool          getIsUpgradeSeparate()          Optional. True, if the gift's upgrade was purchased after the gift was sent; for gifts received on behalf of business accounts only
 * @method int           getUniqueGiftNumber()           Optional. Unique number reserved for this gift when upgraded
 */
class OwnedGiftRegular extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'gift'        => Gift::class,
            'sender_user' => User::class,
            'entities'    => [MessageEntity::class],
        ];
    }
}
