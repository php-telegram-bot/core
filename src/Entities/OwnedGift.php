<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class OwnedGift
 *
 * This object describes a gift received and owned by a user or a chat.
 * Currently, it can be one of
 * - OwnedGiftRegular
 * - OwnedGiftUnique
 *
 * @link https://core.telegram.org/bots/api#ownedgift
 */
class OwnedGift extends Entity implements Factory
{
    /**
     * @param array $data
     * @param string $bot_username
     *
     * @return OwnedGiftRegular|OwnedGiftUnique
     */
    public static function make(array $data, string $bot_username)
    {
        $type = $data['type'] ?? null;
        if ($type === 'unique') {
            return new OwnedGiftUnique($data, $bot_username);
        }

        return new OwnedGiftRegular($data, $bot_username);
    }
}
