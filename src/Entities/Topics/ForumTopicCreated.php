<?php

namespace Longman\TelegramBot\Entities\Topics;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class ForumTopicCreated
 *
 * This object represents a service message about a new forum topic created in the chat.
 *
 * @link https://core.telegram.org/bots/api#forumtopiccreated
 *
 * @method string getName()              Name of the topic
 * @method int    getIconColor()         Color of the topic icon in RGB format
 * @method string getIconCustomEmojiId() Optional. Unique identifier of the custom emoji shown as the topic icon
 */
class ForumTopicCreated extends Entity
{

}
