<?php

namespace Longman\TelegramBot\Entities\ReactionType;

use Longman\TelegramBot\Entities\Entity;

class Factory extends \Longman\TelegramBot\Entities\Factory
{
    public static function make(array $data, string $bot_username): Entity
    {
        $type = [
            'emoji'        => ReactionTypeEmoji::class,
            'custom_emoji' => ReactionTypeCustomEmoji::class,
        ];

        if (!isset($type[$data['type'] ?? ''])) {
            return new ReactionTypeNotImplemented($data, $bot_username);
        }

        $class = $type[$data['type']];
        return new $class($data, $bot_username);
    }
}
