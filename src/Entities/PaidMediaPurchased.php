<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class PaidMediaPurchased
 *
 * This object contains information about a paid media purchase.
 *
 * @link https://core.telegram.org/bots/api#paidmediapurchased
 *
 * @method User   getFrom()         User who purchased the media
 * @method string getPaidMediaPayload() Bot-specified payload for the paid media
 */
class PaidMediaPurchased extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'from' => User::class,
        ];
    }
}
