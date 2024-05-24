<?php

namespace Longman\TelegramBot\Entities\MessageOrigin;

use Longman\TelegramBot\Entities\Entity;

/**
 * @method string getType() Type of the message origin
 * @method int    getDate() Date the message was sent originally in Unix time
 */
class MessageOriginNotImplemented extends Entity implements MessageOrigin
{

}
