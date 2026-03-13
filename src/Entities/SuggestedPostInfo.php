<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class SuggestedPostInfo
 *
 * This object describes a suggested post.
 *
 * @link https://core.telegram.org/bots/api#suggestedpostinfo
 *
 * @method SuggestedPostPrice      getPrice()   Optional. Price of the suggested post
 * @method SuggestedPostParameters getParameters() Optional. Parameters for the suggested post
 */
class SuggestedPostInfo extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'price'      => SuggestedPostPrice::class,
            'parameters' => SuggestedPostParameters::class,
        ];
    }
}
