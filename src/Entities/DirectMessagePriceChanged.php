<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class DirectMessagePriceChanged
 *
 * Describes a service message about a change in the price of direct messages sent to a channel chat.
 *
 * @link https://core.telegram.org/bots/api#directmessagepricechanged
 *
 * @method bool getAreDirectMessagesEnabled() True, if direct messages are enabled for the channel chat; false otherwise
 * @method int  getDirectMessageStarCount()   Optional. The new number of Telegram Stars that must be paid by users for each direct message sent to the channel. Does not apply to users who have been exempted by administrators. Defaults to 0.
 */
class DirectMessagePriceChanged extends Entity
{
}
