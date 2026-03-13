<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class TransactionPartnerTelegramApi
 *
 * Describes a transaction with the Telegram API.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnertelegramapi
 *
 * @method string getType()        Type of the transaction partner, always “telegram_api”
 * @method int    getRequestCount() The number of requests for which the payment was made
 */
class TransactionPartnerTelegramApi extends Entity implements TransactionPartner
{
}
