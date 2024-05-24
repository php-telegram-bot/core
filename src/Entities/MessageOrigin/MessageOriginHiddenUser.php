<?php

namespace Longman\TelegramBot\Entities\MessageOrigin;

use Longman\TelegramBot\Entities\Entity;

/**
 * The message was originally sent by an unknown user.
 *
 * @link https://core.telegram.org/bots/api#messageoriginhiddenuser
 *
 * @method string getType()           Type of the message origin, always “hidden_user”
 * @method int    getDate()           Date the message was sent originally in Unix time
 * @method string getSenderUserName() Name of the user that sent the message originally
 */
class MessageOriginHiddenUser extends Entity implements MessageOrigin
{
    //
}
