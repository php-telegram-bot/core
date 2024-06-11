<?php

namespace PhpTelegramBot\Core\Entities\InputMessageContent;

use PhpTelegramBot\Core\Entities\LinkPreviewOptions;
use PhpTelegramBot\Core\Entities\MessageEntity;

/**
 * @method string                  getMessageText()        Text of the message to be sent, 1-4096 characters
 * @method string|null             getParseMode()          Optional. Mode for parsing entities in the message text. See formatting options for more details.
 * @method MessageEntity[]|null    getEntities()           Optional. List of special entities that appear in message text, which can be specified instead of parse_mode
 * @method LinkPreviewOptions|null getLinkPreviewOptions() Optional. Link preview generation options for the message
 */
class InputTextMessageContent extends InputMessageContent
{
    protected static function subEntities(): array
    {
        return [
            'entities'             => [MessageEntity::class],
            'link_preview_options' => LinkPreviewOptions::class,
        ];
    }
}
