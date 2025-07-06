<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

/**
 * Class SharedUser
 *
 * This object contains information about a user that was shared with the bot using a KeyboardButtonRequestUsers button.
 *
 * @link https://core.telegram.org/bots/api#shareduser
 *
 * @method int         getUserId()    Identifier of the shared user. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so 64-bit integers or double-precision float types are safe for storing this identifier. The bot may not have access to the user and could be unable to use this identifier, unless the user is already known to the bot by some other means.
 * @method string      getFirstName() Optional. First name of the user, if the name was requested by the bot
 * @method string      getLastName()  Optional. Last name of the user, if the name was requested by the bot
 * @method string      getUsername()  Optional. Username of the user, if the username was requested by the bot
 * @method PhotoSize[] getPhoto()     Optional. Available sizes of the user photo, if the photo was requested by the bot
 */
class SharedUser extends Entity
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
