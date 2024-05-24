<?php

namespace Longman\TelegramBot\Entities\Giveaway;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\Message;

/**
 * This object represents a service message about the completion of a giveaway without public winners.
 *
 * @link https://core.telegram.org/bots/api#giveawaycompleted
 *
 * @method int     getWinnerCount()         Number of winners in the giveaway
 * @method int     getUnclaimedPrizeCount() Optional. Number of undistributed prizes
 * @method Message getGiveawayMessage()     Optional. Message with the giveaway that was completed, if it wasn't deleted
 */
class GiveawayCompleted extends Entity
{
    protected function subEntities(): array
    {
        return [
            'giveaway_message' => Message::class,
        ];
    }
}
