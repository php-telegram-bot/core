<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class SentWebAppMessage
 * Contains information about an inline message sent by a Web App on behalf of a user.
 *
 * @link https://core.telegram.org/bots/api#sentwebappmessage
 *
 * @property string $inline_message_id Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the message.
 *
 * @method string getInlineMessageId() Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the message.
 *
 * @method $this setInlineMessageId(string $inline_message_id) Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the message.
 */
class SentWebAppMessage extends Entity
{

}
