<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class InputChecklistTask
 *
 * Describes a task to add to a checklist.
 *
 * @link https://core.telegram.org/bots/api#inputchecklisttask
 *
 * @method int             getId()           Unique identifier of the task; must be positive and unique among all task identifiers currently present in the checklist
 * @method string          getText()         Text of the task; 1-100 characters after entities parsing
 * @method string          getParseMode()    Optional. Mode for parsing entities in the text. See formatting options for more details.
 * @method MessageEntity[] getTextEntities() Optional. List of special entities that appear in the text, which can be specified instead of parse_mode. Currently, only bold, italic, underline, strikethrough, spoiler, and custom_emoji entities are allowed.
 */
class InputChecklistTask extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'text_entities' => [MessageEntity::class],
        ];
    }
}
