<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method string               getText()          Option text, 1-100 characters
 * @method string|null          getTextParseMode() Optional. Mode for parsing entities in the text. See formatting options for more details. Currently, only custom emoji entities are allowed
 * @method MessageEntity[]|null getTextEntities()  Optional. A JSON-serialized list of special entities that appear in the poll option text. It can be specified instead of text_parse_mode
 */
class InputPollOption extends Entity
{
    protected static function subEntities(): array
    {
        return [
            'text_entities' => [MessageEntity::class],
        ];
    }
}
