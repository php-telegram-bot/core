<?php

namespace Longman\TelegramBot\Entities\Giveaway;

use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\User;

/**
 * This object represents a message about the completion of a giveaway with public winners.
 *
 * @link https://core.telegram.org/bots/api#giveawaywinners
 *
 * @method Chat        getChat()                          The chat that created the giveaway
 * @method int         getGiveawayMessageId()             Identifier of the message with the giveaway in the chat
 * @method int         getWinnersSelectionDate()          Point in time (Unix timestamp) when winners of the giveaway were selected
 * @method int         getWinnerCount()                   Total number of winners in the giveaway
 * @method array<User> getWinners()                       List of up to 100 winners of the giveaway
 * @method int         getAdditionalChatCount()           Optional. The number of other chats the user had to join in order to be eligible for the giveaway
 * @method int         getPremiumSubscriptionMonthCount() Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for
 * @method int         getUnclaimedPrizeCount()           Optional. Number of undistributed prizes
 * @method bool        getOnlyNewMembers()                Optional. True, if only users who had joined the chats after the giveaway started were eligible to win
 * @method bool        getWasRefunded()                   Optional. True, if the giveaway was canceled because the payment for it was refunded
 * @method string      getPrizeDescription()              Optional. Description of additional giveaway prize
 */
class GiveawayWinners extends Entity
{
    protected function subEntities(): array
    {
        return [
            'chat'    => Chat::class,
            'winners' => [User::class],
        ];
    }
}
