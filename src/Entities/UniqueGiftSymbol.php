<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UniqueGiftSymbol
 *
 * This object describes the symbol shown on the pattern of a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftsymbol
 *
 * @method string  getName()           Name of the symbol
 * @method Sticker getSticker()        The sticker that represents the unique gift
 * @method int     getRarityPerMille() The number of unique gifts that receive this model for every 1000 gifts upgraded
 */
class UniqueGiftSymbol extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'sticker' => Sticker::class,
        ];
    }
}
