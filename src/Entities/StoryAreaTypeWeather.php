<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StoryAreaTypeWeather
 *
 * Describes a story area containing weather information. Currently, a story can have up to 3 weather areas.
 *
 * @link https://core.telegram.org/bots/api#storyareatypeweather
 *
 * @method string getType()            Type of the area, always “weather”
 * @method float  getTemperature()     Temperature, in degree Celsius
 * @method string getEmoji()           Emoji representing the weather
 * @method int    getBackgroundColor() A color of the area background in the ARGB format
 */
class StoryAreaTypeWeather extends StoryAreaType
{
}
