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
 * This object contains information about a chat boost.
 *
 * @link https://core.telegram.org/bots/api#chatboost
 *
 * @method string          getBoostId()        Unique identifier of the boost
 * @method int             getAddDate()        Point in time (Unix timestamp) when the chat was boosted
 * @method int             getExpirationDate() Point in time (Unix timestamp) when the boost will automatically expire, unless the booster's Telegram Premium subscription is prolonged
 * @method ChatBoostSource getSource()         Source of the added boost
 */
class ChatBoost extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'source' => ChatBoostSourceFactory::class,
        ];
    }
}
