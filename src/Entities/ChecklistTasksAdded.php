<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class ChecklistTasksAdded
 *
 * Describes a service message about tasks added to a checklist.
 *
 * @link https://core.telegram.org/bots/api#checklisttasksadded
 *
 * @method Message         getChecklistMessage() Optional. Message containing the checklist to which the tasks were added. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method ChecklistTask[] getTasks()            List of tasks added to the checklist
 */
class ChecklistTasksAdded extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'checklist_message' => Message::class,
            'tasks'             => [ChecklistTask::class],
        ];
    }
}
