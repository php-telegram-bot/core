<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class PreparedInlineMessage
 *
 * This object represents a message that can be sent by a user from a Mini App via the method shareMessage.
 *
 * @link https://core.telegram.org/bots/api#preparedinlinemessage
 *
 * @method string getId()         Unique identifier of the prepared message
 * @method int    getExpirationDate() Expiration date of the prepared message in Unix time
 */
class PreparedInlineMessage extends Entity
{
}
