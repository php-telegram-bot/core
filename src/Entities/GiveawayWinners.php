<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

/**
 * @method Chat        getChat()                                                The chat that created the giveaway
 * @method int         getGiveawayMessageId()                                   Identifier of the message with the giveaway in the chat
 * @method int         getWinnersSelectionDate() Point in time (Unix timestamp) when winners of the giveaway were selected
 * @method int         getWinnerCount()                                         Total number of winners in the giveaway
 * @method User[]      getWinners()                                             List of up to 100 winners of the giveaway
 * @method int|null    getAdditionalChatCount()                                 Optional. The number of other chats the user had to join in order to be eligible for the giveaway
 * @method int|null    getPremiumSubscriptionMonthCount()                       Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for
 * @method int|null    getUnclaimedPrizeCount()                                 Optional. Number of undistributed prizes
 * @method true|null   getOnlyNewMembers()                                      Optional. True, if only users who had joined the chats after the giveaway started were eligible to win
 * @method bool        wasRefunded()                                            Optional. True, if the giveaway was canceled because the payment for it was refunded
 * @method string|null getPrizeDescription()                                    Optional. Description of additional giveaway prize
 */
class GiveawayWinners extends Entity implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'chat'    => Chat::class,
            'winners' => [User::class],
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'was_refunded' => false,
        ];
    }
}
