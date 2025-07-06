<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\Background;

/**
 * Class BackgroundFill
 *
 * The background is automatically filled based on the selected colors.
 *
 * @link https://core.telegram.org/bots/api#backgroundfill
 *
 * @method string getType()          Type of the fill, always “fill”
 * @method int    getTopColor()      The color of the top gradient fill as a RGB24 value
 * @method int    getBottomColor()   The color of the bottom gradient fill as a RGB24 value
 * @method int    getRotationAngle() Clockwise rotation angle of the background fill in degrees; 0-359
 */
class BackgroundFill extends BackgroundType
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'fill';
        parent::__construct($data);
    }
}
