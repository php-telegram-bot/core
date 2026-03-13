<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StoryAreaTypeUniqueGift
 *
 * Describes a story area pointing to a unique gift. Currently, a story can have at most 1 unique gift area.
 *
 * @link https://core.telegram.org/bots/api#storyareatypeuniquegift
 *
 * @method string getType() Type of the area, always “unique_gift”
 * @method string getName() Unique name of the gift
 */
class StoryAreaTypeUniqueGift extends StoryAreaType
{
}
