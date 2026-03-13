<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class ChecklistTasksDone
 *
 * Describes a service message about checklist tasks marked as done or not done.
 *
 * @link https://core.telegram.org/bots/api#checklisttasksdone
 *
 * @method Message getChecklistMessage()        Optional. Message containing the checklist whose tasks were marked as done or not done. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method int[]   getMarkedAsDoneTaskIds()     Optional. Identifiers of the tasks that were marked as done
 * @method int[]   getMarkedAsNotDoneTaskIds()  Optional. Identifiers of the tasks that were marked as not done
 */
class ChecklistTasksDone extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'checklist_message' => Message::class,
        ];
    }
}
