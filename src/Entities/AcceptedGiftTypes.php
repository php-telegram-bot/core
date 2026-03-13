<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class AcceptedGiftTypes
 *
 * This object describes the types of gifts that can be gifted to a user or a chat.
 *
 * @link https://core.telegram.org/bots/api#acceptedgifttypes
 *
 * @method bool getUnlimitedGifts()      True, if unlimited regular gifts are accepted
 * @method bool getLimitedGifts()        True, if limited regular gifts are accepted
 * @method bool getUniqueGifts()         True, if unique gifts or gifts that can be upgraded to unique for free are accepted
 * @method bool getPremiumSubscription() True, if a Telegram Premium subscription is accepted
 * @method bool getGiftsFromChannels()   True, if transfers of unique gifts from channels are accepted
 */
class AcceptedGiftTypes extends Entity
{
}
