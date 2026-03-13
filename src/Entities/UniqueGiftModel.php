<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UniqueGiftModel
 *
 * This object describes the model of a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftmodel
 *
 * @method string  getName()           Name of the model
 * @method Sticker getSticker()        The sticker that represents the unique gift
 * @method int     getRarityPerMille() The number of unique gifts that receive this model for every 1000 gifts upgraded
 * @method string  getRarity()         Optional. The rarity of the unique gift model
 */
class UniqueGiftModel extends Entity
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
