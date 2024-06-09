<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string|null getQuery()             Optional. The default inline query to be inserted in the input field. If left empty, only the bot's username will be inserted
 * @method bool|null   getAllowUserChats()    Optional. True, if private chats with users can be chosen
 * @method bool|null   getAllowBotChats()     Optional. True, if private chats with bots can be chosen
 * @method bool|null   getAllowGroupChats()   Optional. True, if group and supergroup chats can be chosen
 * @method bool|null   getAllowChannelChats() Optional. True, if channel chats can be chosen
 */
class SwitchInlineQueryChosenChat extends Entity
{
    //
}
