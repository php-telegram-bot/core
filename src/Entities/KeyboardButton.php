<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string                          getText()            Text of the button. If none of the optional fields are used, it will be sent as a message when the button is pressed
 * @method KeyboardButtonRequestUsers|null getRequestUsers()    Optional. If specified, pressing the button will open a list of suitable users. Identifiers of selected users will be sent to the bot in a “users_shared” service message. Available in private chats only.
 * @method KeyboardButtonRequestChat|null  getRequestChat()     Optional. If specified, pressing the button will open a list of suitable chats. Tapping on a chat will send its identifier to the bot in a “chat_shared” service message. Available in private chats only.
 * @method bool|null                       getRequestContact()  Optional. If True, the user's phone number will be sent as a contact when the button is pressed. Available in private chats only.
 * @method bool|null                       getRequestLocation() Optional. If True, the user's current location will be sent when the button is pressed. Available in private chats only.
 * @method KeyboardButtonPollType|null     getRequestPoll()     Optional. If specified, the user will be asked to create a poll and send it to the bot when the button is pressed. Available in private chats only.
 * @method WebAppInfo|null                 getWebApp()          Optional. If specified, the described Web App will be launched when the button is pressed. The Web App will be able to send a “web_app_data” service message. Available in private chats only.
 */
class KeyboardButton extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'request_users' => KeyboardButtonRequestUsers::class,
            'request_chat'  => KeyboardButtonRequestChat::class,
            'request_poll'  => KeyboardButtonPollType::class,
            'web_app'       => WebAppInfo::class,
        ];
    }
}
