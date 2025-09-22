<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

/**
 * Class PollOption
 *
 * This entity contains information about one answer option in a poll.
 *
 * @link https://core.telegram.org/bots/api#polloption
 *
 * @method string          getText()          Option text, 1-100 characters
 * @method int             getVoterCount()    Number of users that voted for this option
 * @method MessageEntity[] getTextEntities()  Optional. Special entities that appear in the option text. Currently, only custom emoji entities are allowed in poll option texts
 */
class PollOption extends Entity
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
