<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class InputChecklist
 *
 * Describes a checklist to create.
 *
 * @link https://core.telegram.org/bots/api#inputchecklist
 *
 * @method string               getTitle()                       Title of the checklist; 1-255 characters after entities parsing
 * @method string               getParseMode()                   Optional. Mode for parsing entities in the title. See formatting options for more details.
 * @method MessageEntity[]      getTitleEntities()               Optional. List of special entities that appear in the title, which can be specified instead of parse_mode. Currently, only bold, italic, underline, strikethrough, spoiler, and custom_emoji entities are allowed.
 * @method InputChecklistTask[] getTasks()                       List of 1-30 tasks in the checklist
 * @method bool                 getOthersCanAddTasks()           Optional. Pass True if other users can add tasks to the checklist
 * @method bool                 getOthersCanMarkTasksAsDone()    Optional. Pass True if other users can mark tasks as done or not done in the checklist
 */
class InputChecklist extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'title_entities' => [MessageEntity::class],
            'tasks'          => [InputChecklistTask::class],
        ];
    }
}
