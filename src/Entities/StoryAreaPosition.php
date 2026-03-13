<?php

namespace Longman\TelegramBot\Entities;

/**
 * Class StoryAreaPosition
 *
 * Describes the position of a clickable area within a story.
 *
 * @link https://core.telegram.org/bots/api#storyareaposition
 *
 * @method float getXPercentage()            The abscissa of the area's center, as a percentage of the media width
 * @method float getYPercentage()            The ordinate of the area's center, as a percentage of the media height
 * @method float getWidthPercentage()        The width of the area's rectangle, as a percentage of the media width
 * @method float getHeightPercentage()       The height of the area's rectangle, as a percentage of the media height
 * @method float getRotationAngle()          The clockwise rotation angle of the rectangle, in degrees; 0-360
 * @method float getCornerRadiusPercentage() The radius of the rectangle corner rounding, as a percentage of the media width
 */
class StoryAreaPosition extends Entity
{
}
