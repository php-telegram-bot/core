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
 * Class Poll
 *
 * This entity contains information about a poll.
 *
 * @link https://core.telegram.org/bots/api#poll
 *
 * @method string       getId()       Unique poll identifier
 * @method string       getQuestion() Poll question, 1-255 characters
 * @method PollOption[] getOptions()  List of poll options
 * @method bool         getIsClosed() True, if the poll is closed
 */
class Poll extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'options' => [PollOption::class],
        ];
    }
}
