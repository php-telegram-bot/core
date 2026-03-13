<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class TransactionPartnerUser
 *
 * Describes a transaction with a user.
 *
 * @link https://core.telegram.org/bots/api#transactionpartneruser
 *
 * @method string        getType()                        Type of the transaction partner, always “user”
 * @method string        getTransactionType()             Type of the transaction, currently one of “invoice_payment”, “paid_media_payment”, “gift_purchase”, “premium_purchase”, “business_account_transfer”
 * @method User          getUser()                        Information about the user
 * @method AffiliateInfo getAffiliate()                   Optional. Information about the affiliate that received a commission via this transaction. Can be available only for “invoice_payment” and “paid_media_payment” transactions.
 * @method string        getInvoicePayload()              Optional. Bot-specified invoice payload. Can be available only for “invoice_payment” transactions.
 * @method int           getSubscriptionPeriod()          Optional. The duration of the paid subscription. Can be available only for “invoice_payment” transactions.
 * @method PaidMedia[]   getPaidMedia()                   Optional. Information about the paid media bought by the user; for “paid_media_payment” transactions only
 * @method string        getPaidMediaPayload()            Optional. Bot-specified paid media payload. Can be available only for “paid_media_payment” transactions.
 * @method Gift          getGift()                        Optional. The gift sent to the user by the bot; for “gift_purchase” transactions only
 * @method int           getPremiumSubscriptionDuration() Optional. Number of months the gifted Telegram Premium subscription will be active for; for “premium_purchase” transactions only
 * @method Gift          getPaidGift()                    Optional. The gift bought by the user; for “paid_media_payment” transactions only
 */
class TransactionPartnerUser extends Entity implements TransactionPartner
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'user'       => User::class,
            'affiliate'  => AffiliateInfo::class,
            'paid_media' => [PaidMedia\PaidMedia::class],
            'gift'       => Gift::class,
            'paid_gift'  => Gift::class,
        ];
    }
}
