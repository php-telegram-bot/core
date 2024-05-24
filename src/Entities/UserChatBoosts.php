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
 * This object represents a list of boosts added to a chat by a user.
 *
 * @link https://core.telegram.org/bots/api#userchatboosts
 *
 * @method ChatBoost[] getBoosts() The list of boosts added to the chat by the user
 */
class UserChatBoosts extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'boosts' => [ChatBoost::class],
        ];
    }
}
