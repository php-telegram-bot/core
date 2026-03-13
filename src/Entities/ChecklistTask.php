<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class ChecklistTask
 *
 * Describes a task in a checklist.
 *
 * @link https://core.telegram.org/bots/api#checklisttask
 *
 * @method int             getId()              Unique identifier of the task
 * @method string          getText()            Text of the task
 * @method MessageEntity[] getTextEntities()    Optional. Special entities that appear in the task text
 * @method User            getCompletedByUser() Optional. User that completed the task; omitted if the task wasn't completed by a user
 * @method Chat            getCompletedByChat() Optional. Chat that completed the task; omitted if the task wasn't completed by a chat
 * @method int             getCompletionDate()  Optional. Point in time (Unix timestamp) when the task was completed; 0 if the task wasn't completed
 */
class ChecklistTask extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'text_entities'     => [MessageEntity::class],
            'completed_by_user' => User::class,
            'completed_by_chat' => Chat::class,
        ];
    }
}
