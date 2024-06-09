<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method Chat[]        getChats()                                               The list of chats which the user must join to participate in the giveaway
 * @method int           getWinnersSelectionDate() Point in time (Unix timestamp) when winners of the giveaway will be selected
 * @method int           getWinnerCount()                                         The number of users which are supposed to be selected as winners of the giveaway
 * @method true|null     getOnlyNewMembers()                                      Optional. True, if only users who join the chats after the giveaway started should be eligible to win
 * @method true|null     getHasPublicWinners()                                    Optional. True, if the list of giveaway winners will be visible to everyone
 * @method string|null   getPrizeDescription()                                    Optional. Description of additional giveaway prize
 * @method string[]|null getCountryCodes()                                        Optional. A list of two-letter ISO 3166-1 alpha-2 country codes indicating the countries from which eligible users for the giveaway must come. If empty, then all users can participate in the giveaway. Users with a phone number that was bought on Fragment can always participate in giveaways.
 * @method int|null      getPremiumSubscriptionMonthCount()                       Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for
 */
class Giveaway extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'chats' => [Chat::class],
        ];
    }
}
