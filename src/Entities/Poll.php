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
 * @method string          getId()                    Unique poll identifier
 * @method string          getQuestion()              Poll question, 1-255 characters
 * @method PollOption[]    getOptions()               List of poll options
 * @method int             getTotalVoterCount()       Total number of users that voted in the poll
 * @method bool            getIsClosed()              True, if the poll is closed
 * @method bool            getIsAnonymous()           True, if the poll is anonymous
 * @method string          getType()                  Poll type, currently can be “regular” or “quiz”
 * @method bool            getAllowsMultipleAnswers() True, if the poll allows multiple answers
 * @method int             getCorrectOptionId()       Optional. 0-based identifier of the correct answer option. Available only for polls in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.
 * @method string          getExplanation()           Optional. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
 * @method MessageEntity[] getExplanationEntities()   Optional. Special entities like usernames, URLs, bot commands, etc. that appear in the explanation
 * @method int             getOpenPeriod()            Optional. Amount of time in seconds the poll will be active after creation
 * @method int             getCloseDate()             Optional. Point in time (Unix timestamp) when the poll will be automatically closed
 */
class Poll extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'options'              => [PollOption::class],
            'explanation_entities' => [MessageEntity::class],
        ];
    }
}
