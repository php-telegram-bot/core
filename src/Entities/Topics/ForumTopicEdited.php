<?php

namespace Longman\TelegramBot\Entities\Topics;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class ForumTopicEdited
 *
 * This object represents a service message about an edited forum topic.
 *
 * @link https://core.telegram.org/bots/api#forumtopicedited
 *
 * @method string getName()              Optional. New name of the topic, if it was edited
 * @method string getIconCustomEmojiId() Optional. New identifier of the custom emoji shown as the topic icon, if it was edited; an empty string if the icon was removed
 */
class ForumTopicEdited extends Entity
{

}
