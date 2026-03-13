<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StoryArea
 *
 * Describes a clickable area on a story media.
 *
 * @link https://core.telegram.org/bots/api#storyarea
 *
 * @method StoryAreaPosition getPosition() Position of the area
 * @method StoryAreaType     getType()     Type of the area
 */
class StoryArea extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'position' => StoryAreaPosition::class,
            // 'type' is polymorphic, so we need a factory.
            'type'     => StoryAreaType::class,
        ];
    }
}
