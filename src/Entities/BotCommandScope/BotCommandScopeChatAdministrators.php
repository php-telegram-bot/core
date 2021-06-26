<?php

namespace Longman\TelegramBot\Entities\BotCommandScope;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class BotCommandScopeChatAdministrators
 *
 * @link https://core.telegram.org/bots/api#botcommandscopechatadministrators
 *
 * <code>
 * $data = [
 *   'chat_id' => '123456'
 * ];
 * </code>
 *
 * @method string getType()   Scope type, must be chat_administrators
 * @method string getChatId() Unique identifier for the target chat or username of the target supergroup (in the format @supergroupusername)
 *
 * @method $this setChatId(string $chat_id) Unique identifier for the target chat or username of the target supergroup (in the format @supergroupusername)
 */
class BotCommandScopeChatAdministrators extends Entity implements BotCommandScope
{
    public function __construct(array $data = [])
    {
        $data['type'] = 'chat_administrators';
        parent::__construct($data);
    }
}
