<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method int       getRequestId()       Signed 32-bit identifier of the request that will be received back in the UsersShared object. Must be unique within the message
 * @method bool|null getUserIsBot()       Optional. Pass True to request bots, pass False to request regular users. If not specified, no additional restrictions are applied.
 * @method bool|null getUserIsPremium()   Optional. Pass True to request premium users, pass False to request non-premium users. If not specified, no additional restrictions are applied.
 * @method int|null  getMaxQuantity()     Optional. The maximum number of users to be selected; 1-10. Defaults to 1.
 * @method bool|null getRequestName()     Optional. Pass True to request the users' first and last names
 * @method bool|null getRequestUsername() Optional. Pass True to request the users' usernames
 * @method bool|null getRequestPhoto()    Optional. Pass True to request the users' photos
 */
class KeyboardButtonRequestUsers extends Entity
{
    //
}
