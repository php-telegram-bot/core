<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method int          getWinnerCount()         Number of winners in the giveaway
 * @method int|null     getUnclaimedPrizeCount() Optional. Number of undistributed prizes
 * @method Message|null getGiveawayMessage()     Optional. Message with the giveaway that was completed, if it wasn't deleted
 */
class GiveawayCompleted extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'giveaway_message' => Message::class,
        ];
    }
}
