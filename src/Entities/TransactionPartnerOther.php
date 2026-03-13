<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class TransactionPartnerOther
 *
 * Describes a transaction with an unknown source or recipient.
 *
 * @link https://core.telegram.org/bots/api#transactionpartnerother
 *
 * @method string getType() Type of the transaction partner, always “other”
 */
class TransactionPartnerOther extends Entity implements TransactionPartner
{
}
