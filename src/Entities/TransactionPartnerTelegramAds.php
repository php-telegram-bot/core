<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class TransactionPartnerTelegramAds
 *
 * Describes a transaction with the Telegram Ads platform.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnertelegramads
 *
 * @method string getType() Type of the transaction partner, always “telegram_ads”
 */
class TransactionPartnerTelegramAds extends Entity implements TransactionPartner
{
}
