<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StoryAreaTypeLocation
 *
 * Describes a story area pointing to a location. Currently, a story can have up to 10 location areas.
 *
 * @link https://core.telegram.org/bots/api#storyareatypelocation
 *
 * @method string          getType()      Type of the area, always “location”
 * @method float           getLatitude()  Location latitude in degrees
 * @method float           getLongitude() Location longitude in degrees
 * @method LocationAddress getAddress()   Optional. Address of the location
 */
class StoryAreaTypeLocation extends StoryAreaType
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities(): array
    {
        return [
            'address' => LocationAddress::class,
        ];
    }
}
