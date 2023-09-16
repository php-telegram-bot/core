<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class ChatShared
 *
 * This object contains information about the chat whose identifier was shared with the bot using a KeyboardButtonRequestChat button.
 *
 * @link https://core.telegram.org/bots/api#chatshared
 *
 * @method int getRequestId() Identifier of the request
 * @method int getChatId()    Identifier of the shared chat.
 */
class ChatShared extends Entity
{

}
