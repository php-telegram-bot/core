<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StoryAreaTypeSuggestedReaction
 *
 * Describes a story area pointing to a suggested reaction. Currently, a story can have up to 5 suggested reaction areas.
 *
 * @link https://core.telegram.org/bots/api#storyareatypesuggestedreaction
 *
 * @method string       getType()         Type of the area, always “suggested_reaction”
 * @method ReactionType getReactionType() Type of the reaction
 * @method bool         getIsDark()       Optional. Pass True if the reaction area has a dark background
 * @method bool         getIsFlipped()    Optional. Pass True if reaction area corner is flipped
 */
class StoryAreaTypeSuggestedReaction extends StoryAreaType
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'reaction_type' => ReactionType::class,
        ];
    }
}
