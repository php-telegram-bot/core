<?php

namespace Longman\TelegramBot\Entities;

/**
 * This object defines the criteria used to request suitable users. The identifiers of the selected users will be shared with the bot when the corresponding button is pressed.
 *
 * @link https://core.telegram.org/bots/api#keyboardbuttonrequestusers
 *
 * @method int  getRequestId()     Signed 32-bit identifier of the request, which will be received back in the UserShared object. Must be unique within the message
 * @method bool getUserIsBot()     Optional. Pass True to request a bot, pass False to request a regular user. If not specified, no additional restrictions are applied.
 * @method bool getUserIsPremium() Optional. Pass True to request a premium user, pass False to request a non-premium user. If not specified, no additional restrictions are applied.
 * @method int  getMaxQuantity()   Optional. The maximum number of users to be selected; 1-10. Defaults to 1.
 *
 * @method $this setRequestId(int $request_id)           Signed 32-bit identifier of the request, which will be received back in the UserShared object. Must be unique within the message
 * @method $this setUserIsBot(bool $user_is_bot)         Optional. Pass True to request a bot, pass False to request a regular user. If not specified, no additional restrictions are applied.
 * @method $this setUserIsPremium(bool $user_is_premium) Optional. Pass True to request a premium user, pass False to request a non-premium user. If not specified, no additional restrictions are applied.
 * @method int   setMaxQuantity(int $set_max_quantity)   Optional. The maximum number of users to be selected; 1-10. Defaults to 1.
 */
class KeyboardButtonRequestUsers extends Entity
{

}
