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

use Longman\TelegramBot\Entities\Background\BackgroundType;

/**
 * Class ChatBackground
 *
 * This object represents a chat background.
 *
 * @link https://core.telegram.org/bots/api#chatbackground
 *
 * @method BackgroundType getType() Type of the background
 */
class ChatBackground extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'type' => BackgroundType::class,
        ];
    }
}
