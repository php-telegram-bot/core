<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class ForumTopic
 *
 * This object represents a forum topic.
 *
 * @link https://core.telegram.org/bots/api#forumtopic
 *
 * @method int    getMessageThreadId()   Unique identifier of the forum topic
 * @method string getName()              Name of the topic
 * @method int    getIconColor()         Color of the topic icon in RGB format
 * @method string getIconCustomEmojiId() Optional. Unique identifier of the custom emoji shown as the topic icon
 * @method bool   getIsNameImplicit()    Optional. True, if the name of the topic wasn't specified explicitly by its creator and likely needs to be changed by the bot
 */
class ForumTopic extends Entity
{
}
