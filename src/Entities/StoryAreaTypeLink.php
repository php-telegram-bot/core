<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StoryAreaTypeLink
 *
 * Describes a story area pointing to an HTTP or tg:// link. Currently, a story can have up to 3 link areas.
 *
 * @link https://core.telegram.org/bots/api#storyareatypelink
 *
 * @method string getType() Type of the area, always “link”
 * @method string getUrl()  HTTP or tg:// URL to be opened when the area is clicked
 */
class StoryAreaTypeLink extends StoryAreaType
{
}
