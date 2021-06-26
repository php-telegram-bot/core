<?php

namespace Longman\TelegramBot\Entities\BotCommandScope;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class BotCommandScopeAllGroupChats
 *
 * @link https://core.telegram.org/bots/api#botcommandscopeallgroupchats
 */
class BotCommandScopeAllGroupChats extends Entity implements BotCommandScope
{
    public function __construct(array $data = [])
    {
        $data['type'] = 'all_group_chats';
        parent::__construct($data);
    }
}
