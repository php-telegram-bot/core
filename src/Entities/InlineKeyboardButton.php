<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string                           getText()                        Label text on the button
 * @method string|null                      getUrl()                         Optional. HTTP or tg:// URL to be opened when the button is pressed. Links tg://user?id=<user_id> can be used to mention a user by their identifier without using a username, if this is allowed by their privacy settings.
 * @method string|null                      getCallbackData()                Optional. Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes. Not supported for messages sent on behalf of a Telegram Business account.
 * @method WebAppInfo|null                  getWebApp()                      Optional. Description of the Web App that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery. Available only in private chats between a user and the bot. Not supported for messages sent on behalf of a Telegram Business account.
 * @method LoginUrl|null                    getLoginUrl()                    Optional. An HTTPS URL used to automatically authorize the user. Can be used as a replacement for the Telegram Login Widget.
 * @method string|null                      getSwitchInlineQuery()           Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. May be empty, in which case just the bot's username will be inserted. Not supported for messages sent on behalf of a Telegram Business account.
 * @method SwitchInlineQueryChosenChat|null getSwitchInlineQueryChosenChat() Optional. If set, pressing the button will prompt the user to select one of their chats of the specified type, open that chat and insert the bot's username and the specified inline query in the input field. Not supported for messages sent on behalf of a Telegram Business account.
 * @method CallbackGame|null                getCallbackGame()                Optional. Description of the game that will be launched when the user presses the button.
 * @method bool|null                        getPay()                         Optional. Specify True, to send a Pay button. Substrings “⭐” and “XTR” in the buttons's text will be replaced with a Telegram Star icon.
 */
class InlineKeyboardButton extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'web_app'                         => WebAppInfo::class,
            'login_url'                       => LoginUrl::class,
            'switch_inline_query_chosen_chat' => SwitchInlineQueryChosenChat::class,
            'callback_game'                   => CallbackGame::class,
        ];
    }
}
