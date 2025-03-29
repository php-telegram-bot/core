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
 * Class Story
 *
 * @link https://core.telegram.org/bots/api#story
 *
 * @method Chat getChat() Chat that posted the story
 * @method int  getId()   Unique identifier for the story in the chat
 */
class Story extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'chat' => Chat::class,
        ];
    }
}
