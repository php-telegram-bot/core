<?php

namespace Longman\TelegramBot\Entities\Message;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\Message;

class Factory extends \Longman\TelegramBot\Entities\Factory
{

    public static function make(array $data, string $bot_username): Entity
    {
        if ($data['date'] === 0) {
            $class = InaccessibleMessage::class;
        } else {
            $class = Message::class;
        }

        return new $class($data, $bot_username);
    }
}
