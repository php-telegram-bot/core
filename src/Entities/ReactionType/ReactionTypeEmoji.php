<?php

namespace Longman\TelegramBot\Entities\ReactionType;

use Longman\TelegramBot\Entities\Entity;

/**
 * The reaction is based on an emoji.
 *
 * @link https://core.telegram.org/bots/api#reactiontypeemoji
 *
 * @method string getType()  Type of the reaction, always “emoji”
 * @method string getEmoji() Reaction emoji.
 */
class ReactionTypeEmoji extends Entity implements ReactionType
{
    public function __construct(array $data = [])
    {
        $data['type'] = 'emoji';
        parent::__construct($data);
    }
}
