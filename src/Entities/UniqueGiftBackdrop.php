<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UniqueGiftBackdrop
 *
 * This object describes the backdrop of a unique gift.
 *
 * @link https://core.telegram.org/bots/api#uniquegiftbackdrop
 *
 * @method string                   getName()           Name of the backdrop
 * @method UniqueGiftBackdropColors getColors()         Colors of the backdrop
 * @method int                      getRarityPerMille() The number of unique gifts that receive this backdrop for every 1000 gifts upgraded
 */
class UniqueGiftBackdrop extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'colors' => UniqueGiftBackdropColors::class,
        ];
    }
}
