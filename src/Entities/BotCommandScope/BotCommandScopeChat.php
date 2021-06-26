<?php

namespace Longman\TelegramBot\Entities\BotCommandScope;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class BotCommandScopeChat
 *
 * @link https://core.telegram.org/bots/api#botcommandscopechat
 *
 * <code>
 * $data = [
 *   'chat_id' => '123456'
 * ];
 * </code>
 *
 * @method string getType()   Scope type, must be chat
 * @method string getChatId() Unique identifier for the target chat or username of the target supergroup (in the format @supergroupusername)
 *
 * @method $this setChatId(string $chat_id) Unique identifier for the target chat or username of the target supergroup (in the format @supergroupusername)
 */
class BotCommandScopeChat extends Entity implements BotCommandScope
{
    public function __construct(array $data = [])
    {
        $data['type'] = 'chat';
        parent::__construct($data);
    }
}
