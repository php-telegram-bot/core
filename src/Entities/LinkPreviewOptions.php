<?php

namespace PhpTelegramBot\Core\Entities;

/**
 * @method bool|null   getIsDisabled()       Optional. True, if the link preview is disabled
 * @method string|null getUrl()              Optional. URL to use for the link preview. If empty, then the first URL found in the message text will be used
 * @method bool|null   getPreferSmallMedia() Optional. True, if the media in the link preview is supposed to be shrunk; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 * @method bool|null   getPreferLargeMedia() Optional. True, if the media in the link preview is supposed to be enlarged; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 * @method bool|null   getShowAboveText()    Optional. True, if the link preview must be shown above the message text; otherwise, the link preview will be shown below the message text
 */
class LinkPreviewOptions extends Entity
{
    //
}
