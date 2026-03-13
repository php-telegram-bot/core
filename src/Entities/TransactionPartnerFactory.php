<?php

namespace Longman\TelegramBot\Entities;

class TransactionPartnerFactory extends Factory
{
    public static function make(array $data, string $bot_username): Entity
    {
        $type = [
            'user'              => TransactionPartnerUser::class,
            'chat'              => TransactionPartnerChat::class,
            'telegram_api'      => TransactionPartnerTelegramApi::class,
            'telegram_ads'      => TransactionPartnerTelegramAds::class,
            'affiliate_program' => TransactionPartnerAffiliateProgram::class,
            'fragment'          => TransactionPartnerFragment::class,
            'other'             => TransactionPartnerOther::class,
        ];

        if (!isset($type[$data['type'] ?? ''])) {
            return new class($data, $bot_username) extends Entity implements TransactionPartner {};
        }

        $class = $type[$data['type']];
        return new $class($data, $bot_username);
    }
}
