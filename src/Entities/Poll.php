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
 * @method string getId()       Unique poll identifier
 * @method string getQuestion() Poll question, 1-255 characters
 * @method bool   getIsClosed() True, if the poll is closed
 */
class Poll extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'options' => PollOption::class,
        ];
    }

    /**
     * List of poll options
     *
     * This method overrides the default getOptions method
     * and returns a nice array of PollOption objects.
     *
     * @return null|PollOption[]
     */
    public function getOptions()
    {
        $pretty_array = $this->makePrettyObjectArray(PollOption::class, 'options');

        return empty($pretty_array) ? null : $pretty_array;
    }
}
