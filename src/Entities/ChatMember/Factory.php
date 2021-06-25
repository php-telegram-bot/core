<?php


namespace Longman\TelegramBot\Entities\ChatMember;


use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Exception\TelegramException;

class Factory extends \Longman\TelegramBot\Entities\Factory
{
    public function make(array $data, string $bot_username): Entity
    {
        switch ($data['status'] ?? '') {
            case 'creator':
                return new ChatMemberOwner($data, $bot_username);
            case 'administrator':
                return new ChatMemberAdministrator($data, $bot_username);
            case 'member':
                return new ChatMemberMember($data, $bot_username);
            case 'restricted':
                return new ChatMemberRestricted($data, $bot_username);
            case 'left':
                return new ChatMemberLeft($data, $bot_username);
            case 'kicked':
                return new ChatMemberBanned($data, $bot_username);
        }

        throw new TelegramException('Unexpected ChatMember type');
    }
}
