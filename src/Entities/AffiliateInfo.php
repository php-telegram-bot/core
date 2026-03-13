<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class AffiliateInfo
 *
 * This object contains information about the affiliate that received a commission via this transaction.
 *
 * @link https://core.telegram.org/bots/api#affiliateinfo
 *
 * @method User   getAffiliateUser() Optional. The bot or the user that received an affiliate commission if it is a user
 * @method Chat   getAffiliateChat() Optional. The chat that received an affiliate commission if it is a chat
 * @method int    getCommissionPerMille() The number of Telegram Stars for each 1000 Telegram Stars transferred to the bot/business account
 * @method int    getAmount()        Integer amount of Telegram Stars received by the affiliate
 * @method int    getNanostarAmount() Optional. The number of 1/10^9 units of Telegram Stars received by the affiliate; for affiliate program commissions only
 */
class AffiliateInfo extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'affiliate_user' => User::class,
            'affiliate_chat' => Chat::class,
        ];
    }
}
