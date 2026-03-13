<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StoryAreaType
 *
 * Describes the type of a clickable area on a story. Currently, it can be one of
 * - StoryAreaTypeLocation
 * - StoryAreaTypeSuggestedReaction
 * - StoryAreaTypeLink
 * - StoryAreaTypeWeather
 * - StoryAreaTypeUniqueGift
 *
 * @link https://core.telegram.org/bots/api#storyareatype
 */
class StoryAreaType extends Entity implements Factory
{
    /**
     * @param array $data
     * @param string $bot_username
     *
     * @return StoryAreaTypeLocation|StoryAreaTypeSuggestedReaction|StoryAreaTypeLink|StoryAreaTypeWeather|StoryAreaTypeUniqueGift
     */
    public static function make(array $data, string $bot_username)
    {
        $type = $data['type'] ?? null;
        switch ($type) {
            case 'location':
                return new StoryAreaTypeLocation($data, $bot_username);
            case 'suggested_reaction':
                return new StoryAreaTypeSuggestedReaction($data, $bot_username);
            case 'link':
                return new StoryAreaTypeLink($data, $bot_username);
            case 'weather':
                return new StoryAreaTypeWeather($data, $bot_username);
            case 'unique_gift':
                return new StoryAreaTypeUniqueGift($data, $bot_username);
        }

        return new self($data, $bot_username);
    }
}
