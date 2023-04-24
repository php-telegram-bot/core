<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class UserShared
 *
 * This object contains information about the user whose identifier was shared with the bot using a KeyboardButtonRequestUser button.
 *
 * @link https://core.telegram.org/bots/api#usershared
 *
 * @method int getRequestId() Identifier of the request
 * @method int getUserId()    Identifier of the shared user.
 */
class UserShared extends Entity
{

}
