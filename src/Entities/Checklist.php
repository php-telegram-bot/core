<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class Checklist
 *
 * Describes a checklist.
 *
 * @link https://core.telegram.org/bots/api#checklist
 *
 * @method string          getTitle()                       Title of the checklist
 * @method MessageEntity[] getTitleEntities()               Optional. Special entities that appear in the checklist title
 * @method ChecklistTask[] getTasks()                       List of tasks in the checklist
 * @method bool            getOthersCanAddTasks()           Optional. True, if users other than the creator of the list can add tasks to the list
 * @method bool            getOthersCanMarkTasksAsDone()    Optional. True, if users other than the creator of the list can mark tasks as done or not done
 */
class Checklist extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'title_entities' => [MessageEntity::class],
            'tasks'          => [ChecklistTask::class],
        ];
    }
}
