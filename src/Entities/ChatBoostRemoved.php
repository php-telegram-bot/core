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

use Longman\TelegramBot\Entities\ChatBoostSource\Factory as ChatBoostSourceFactory;

/**
 * This object represents a boost removed from a chat.
 *
 * @link https://core.telegram.org/bots/api#chatboostremoved
 *
 * @method Chat            getChat()       Chat which was boosted
 * @method string          getBoostId()    Unique identifier of the boost
 * @method int             getRemoveDate() Point in time (Unix timestamp) when the boost was removed
 * @method ChatBoostSource getSource()     Source of the removed boost
 */
class ChatBoostRemoved extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'chat'   => Chat::class,
            'source' => ChatBoostSourceFactory::class,
        ];
    }
}
