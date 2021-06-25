<?php


namespace Longman\TelegramBot\Entities\ChatMember;


use Longman\TelegramBot\Entities\Entity;

class Factory extends \Longman\TelegramBot\Entities\Factory
{
    public function make(array $data, string $bot_username): Entity
    {
        var_dump($data);
    }
}
