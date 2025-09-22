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

use Longman\TelegramBot\Entities\Entity;

/**
 * Class BackgroundType
 *
 * This object represents the type of a background. Currently, it can be one of
 * - BackgroundTypeFill
 * - BackgroundTypeWallpaper
 * - BackgroundTypePattern
 * - BackgroundTypeChatTheme
 *
 * @link https://core.telegram.org/bots/api#backgroundtype
 *
 * @method string getType() Type of the background
 */
class BackgroundType extends Entity
{
    // Further specific properties depend on the actual type of background,
    // which will be handled by subclasses or by checking the 'type' field.
    // For example, BackgroundTypeFill, BackgroundTypeWallpaper, etc.
}
