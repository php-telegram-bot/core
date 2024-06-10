<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method true      getRemoveKeyboard() Requests clients to remove the custom keyboard (user will not be able to summon this keyboard; if you want to hide the keyboard from sight but keep it accessible, use one_time_keyboard in ReplyKeyboardMarkup).
 * @method bool|null getSelective()      Optional. Use this parameter if you want to remove the keyboard for specific users only. Targets: 1), users that are @mentioned in the text of the Message object; 2), if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message.
 */
class ReplyKeyboardRemove extends Entity
{
    //
}
