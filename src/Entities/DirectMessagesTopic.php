<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class DirectMessagesTopic
 *
 * This object describes a topic of a direct messages chat.
 *
 * @link https://core.telegram.org/bots/api#directmessagestopic
 *
 * @method int    getMessageThreadId() Unique identifier of the direct messages chat topic
 * @method string getName()              Name of the topic
 * @method int    getIconColor()         Color of the topic icon in RGB format
 * @method string getIconCustomEmojiId() Optional. Unique identifier of the custom emoji shown as the topic icon
 */
class DirectMessagesTopic extends Entity
{
}
