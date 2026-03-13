<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class Gifts
 *
 * This object represent a list of gifts.
 *
 * @link https://core.telegram.org/bots/api#gifts
 *
 * @method Gift[] getGifts() The list of gifts
 */
class Gifts extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'gifts' => [Gift::class],
        ];
    }
}
