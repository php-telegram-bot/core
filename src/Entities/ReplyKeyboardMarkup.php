<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

/**
 * @method array[]     getKeyboard()              Array of button rows, each represented by an Array of KeyboardButton objects
 * @method bool        isPersistent()             Optional. Requests clients to always show the keyboard when the regular keyboard is hidden. Defaults to false, in which case the custom keyboard can be hidden and opened with a keyboard icon.
 * @method bool|null   getResizeKeyboard()        Optional. Requests clients to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
 * @method bool|null   getOneTimeKeyboard()       Optional. Requests clients to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat - the user can press a special button in the input field to see the custom keyboard again. Defaults to false.
 * @method string|null getInputFieldPlaceholder() Optional. The placeholder to be shown in the input field when the keyboard is active; 1-64 characters
 * @method bool|null   getSelective()             Optional. Use this parameter if you want to show the keyboard to specific users only. Targets: 1), users that are @mentioned in the text of the Message object; 2), if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message.
 */
class ReplyKeyboardMarkup extends Entity implements AllowsBypassingGet
{
    protected static function subEntities(): array
    {
        return [
            'keyboard' => [[KeyboardButton::class]],
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'is_persistent' => false,
        ];
    }
}
