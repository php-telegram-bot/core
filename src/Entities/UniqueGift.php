<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UniqueGift
 *
 * This object describes a unique gift that was upgraded from a regular gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegift
 *
 * @method string             getGiftId()            Identifier of the regular gift from which the gift was upgraded
 * @method string             getBaseName()          Human-readable name of the regular gift from which this unique gift was upgraded
 * @method string             getName()              Unique name of the gift. This name can be used in https://t.me/nft/... links and story areas
 * @method int                getNumber()            Unique number of the upgraded gift among gifts upgraded from the same regular gift
 * @method UniqueGiftModel    getModel()             Model of the gift
 * @method UniqueGiftSymbol   getSymbol()            Symbol of the gift
 * @method UniqueGiftBackdrop getBackdrop()          Backdrop of the gift
 * @method bool               getIsPremium()         Optional. True, if the original regular gift was exclusively purchaseable by Telegram Premium subscribers
 * @method bool               getIsBurned()          Optional. True, if the gift was used to craft another gift and isn't available anymore
 * @method bool               getIsFromBlockchain()  Optional. True, if the gift is assigned from the TON blockchain and can't be resold or transferred in Telegram
 * @method UniqueGiftColors   getColors()            Optional. The color scheme that can be used by the gift's owner for the chat's name, replies to messages and link previews; for business account gifts and gifts that are currently on sale only
 * @method Chat               getPublisherChat()     Optional. Information about the chat that published the gift
 */
class UniqueGift extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'model'          => UniqueGiftModel::class,
            'symbol'         => UniqueGiftSymbol::class,
            'backdrop'       => UniqueGiftBackdrop::class,
            'colors'         => UniqueGiftColors::class,
            'publisher_chat' => Chat::class,
        ];
    }
}
