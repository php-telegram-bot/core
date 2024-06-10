<?php

namespace PhpTelegramBot\Core\Entities;

use PhpTelegramBot\Core\Contracts\AllowsBypassingGet;

/**
 * @method string               getId()                    Unique poll identifier
 * @method string               getQuestion()              Poll question, 1-300 characters
 * @method MessageEntity[]|null getQuestionEntities()      Optional. Special entities that appear in the question. Currently, only custom emoji entities are allowed in poll questions
 * @method PollOption[]         getOptions()               List of poll options
 * @method int                  getTotalVoterCount()       Total number of users that voted in the poll
 * @method bool                 isClosed()                 True, if the poll is closed
 * @method bool                 isAnonymous()              True, if the poll is anonymous
 * @method string               getType()                  Poll type, currently can be “regular” or “quiz”
 * @method bool                 getAllowsMultipleAnswers() True, if the poll allows multiple answers
 * @method int|null             getCorrectOptionId()       Optional. 0-based identifier of the correct answer option. Available only for polls in the quiz mode, which are closed, or was sent (not forwarded), by the bot or to the private chat with the bot.
 * @method string|null          getExplanation()           Optional. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
 * @method MessageEntity[]|null getExplanationEntities()   Optional. Special entities like usernames, URLs, bot commands, etc. that appear in the explanation
 * @method int|null             getOpenPeriod()            Optional. Amount of time in seconds the poll will be active after creation
 * @method int|null             getCloseDate()             Optional. Point in time (Unix timestamp), when the poll will be automatically closed
 */
class Poll extends Entity implements AllowsBypassingGet
{
    public const TYPE_REGULAR = 'regular';

    public const TYPE_QUIZ = 'quiz';

    protected static function subEntities(): array
    {
        return [
            'question_entities'    => [MessageEntity::class],
            'options'              => [PollOption::class],
            'explanation_entities' => [MessageEntity::class],
        ];
    }

    public static function fieldsBypassingGet(): array
    {
        return [
            'is_closed'    => false,
            'is_anonymous' => false,
        ];
    }
}
