<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class Gift
 *
 * This object represents a gift that can be sent by the bot.
 *
 * @link https://core.telegram.org/bots/api#gift
 *
 * @method string         getId()                        Unique identifier of the gift
 * @method Sticker        getSticker()                   The sticker that represents the gift
 * @method int            getStarCount()                 The number of Telegram Stars that must be paid to send the sticker
 * @method int            getUpgradeStarCount()          Optional. The number of Telegram Stars that must be paid to upgrade the gift to a unique one
 * @method bool           getIsPremium()                 Optional. True, if the gift can only be purchased by Telegram Premium subscribers
 * @method bool           getHasColors()                 Optional. True, if the gift can be used (after being upgraded) to customize a user's appearance
 * @method int            getTotalCount()                Optional. The total number of gifts of this type that can be sent by all users; for limited gifts only
 * @method int            getRemainingCount()            Optional. The number of remaining gifts of this type that can be sent by all users; for limited gifts only
 * @method int            getPersonalTotalCount()        Optional. The total number of gifts of this type that can be sent by the bot; for limited gifts only
 * @method int            getPersonalRemainingCount()    Optional. The number of remaining gifts of this type that can be sent by the bot; for limited gifts only
 * @method GiftBackground getBackground()                Optional. Background of the gift
 * @method int            getUniqueGiftVariantCount()    Optional. The total number of different unique gifts that can be obtained by upgrading the gift
 * @method Chat           getPublisherChat()             Optional. Information about the chat that published the gift
 */
class Gift extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'sticker'        => Sticker::class,
            'background'     => GiftBackground::class,
            'publisher_chat' => Chat::class,
        ];
    }
}
