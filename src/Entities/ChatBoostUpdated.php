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
 * This object represents a boost added to a chat or changed.
 *
 * @link https://core.telegram.org/bots/api#chatboostupdated
 *
 * @method Chat      getChat()  Chat which was boosted
 * @method ChatBoost getBoost() Information about the chat boost
 */
class ChatBoostUpdated extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'chat'       => Chat::class,
            'chat_boost' => ChatBoost::class,
        ];
    }
}
