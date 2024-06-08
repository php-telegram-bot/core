<?php

namespace PhpTelegramBot\Core\Types;

class Poll extends Type
{
    /** @var string Unique poll identifier */
    public string $id;

    /** @var string Poll question, 1-300 characters */
    public string $question;

    /** @var array<MessageEntity>|null Optional. Special entities that appear in the question. Currently, only custom emoji entities are allowed in poll questions */
    public ?array $question_entites;

    /** @var array<PollOption> List of poll options */
    public array $options;

    /** @var int Total number of users that voted in the poll */
    public int $total_voter_count;

    /** @var bool True, if the poll is closed */
    public bool $is_closed;

    // ...
}
