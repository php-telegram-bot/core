<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\Poll;

use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Entities\MessageEntity;

/**
 * Class InputPollOption
 *
 * This entity contains information about one answer option in a poll to be sent.
 *
 * @link https://core.telegram.org/bots/api#inputpolloption
 *
 * @method string          getText()                 Option text, 1-100 characters
 * @method MessageEntity[] getTextEntities()         Optional. A JSON-serialized list of special entities that appear in the poll option text. It can be specified instead of text_parse_mode
 */
class InputPollOption extends Entity
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
