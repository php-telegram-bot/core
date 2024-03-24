<?php

namespace Longman\TelegramBot\Entities;

/**
 * This object contains information about the users whose identifiers were shared with the bot using a KeyboardButtonRequestUsers button.
 *
 * @link https://core.telegram.org/bots/api#usersshared
 *
 * @method int   getRequestId() Identifier of the request
 * @method int[] getUserIds()   Identifier of the shared users.
 */
class UsersShared extends Entity
{

}
