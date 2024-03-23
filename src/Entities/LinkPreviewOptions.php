<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

/**
 * Describes the options used for link preview generation.
 *
 * @link https://core.telegram.org/bots/api#linkpreviewoptions
 *
 * @method bool   getIsDisabled()       Optional. True, if the link preview is disabled
 * @method string getUrl()              Optional. URL to use for the link preview. If empty, then the first URL found in the message text will be used
 * @method bool   getPreferSmallMedia() Optional. True, if the media in the link preview is supposed to be shrunk; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 * @method bool   getPreferLargeMedia() Optional. True, if the media in the link preview is supposed to be enlarged; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 * @method bool   getShowAboveText()    Optional. True, if the link preview must be shown above the message text; otherwise, the link preview will be shown below the message text *
 */
class LinkPreviewOptions extends Entity
{

}
