<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class TransactionPartnerAffiliateProgram
 *
 * Describes a transaction with an affiliate program.
 *
 * @link https://core.telegram.org/bots/api#transactionpartneraffiliateprogram
 *
 * @method string getType()       Type of the transaction partner, always “affiliate_program”
 * @method User   getSponsorUser() Optional. Information about the bot that sponsored the affiliate program
 * @method int    getCommissionPerMille() The number of Telegram Stars for each 1000 Telegram Stars transferred to the bot/business account
 */
class TransactionPartnerAffiliateProgram extends Entity implements TransactionPartner
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'sponsor_user' => User::class,
        ];
    }
}
