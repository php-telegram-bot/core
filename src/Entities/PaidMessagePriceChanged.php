<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class PaidMessagePriceChanged
 *
 * Describes a service message about a change in the price of paid messages within a chat.
 *
 * @link https://core.telegram.org/bots/api#paidmessagepricechanged
 *
 * @method int getPaidMessageStarCount() The new number of Telegram Stars that must be paid by non-administrator users of the supergroup chat for each sent message
 */
class PaidMessagePriceChanged extends Entity
{
}
