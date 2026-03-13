<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class ChatShared
 *
 * This object contains information about the chat whose identifier was shared with the bot using a KeyboardButtonRequestChat button.
 *
 * @link https://core.telegram.org/bots/api#chatshared
 *
 * @method int         getRequestId() Identifier of the request
 * @method int         getChatId()    Identifier of the shared chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot may not have access to the chat and could be unable to use this identifier, unless the chat is already known to the bot by some other means.
 * @method string      getTitle()     Optional. Title of the chat, if the title was requested by the bot
 * @method string      getUsername()  Optional. Username of the chat, if the username was requested by the bot
 * @method PhotoSize[] getPhoto()     Optional. Available sizes of the chat photo, if the photo was requested by the bot
 */
class ChatShared extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'photo' => [PhotoSize::class],
        ];
    }
}
