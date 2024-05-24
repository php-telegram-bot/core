<?php

namespace Longman\TelegramBot\Entities\ReactionType;

use Longman\TelegramBot\Entities\Entity;

/**
 * The reaction is based on a custom emoji.
 *
 * @link https://core.telegram.org/bots/api#reactiontypecustomemoji
 *
 * @method string getType()          Type of the reaction, always “custom_emoji”
 * @method string getCustomEmojiId() Custom emoji identifier
 */
class ReactionTypeCustomEmoji extends Entity implements ReactionType
{
    public function __construct(array $data = [])
    {
        $data['type'] = 'custom_emoji';
        parent::__construct($data);
    }
}
