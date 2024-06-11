<?php

namespace PhpTelegramBot\Core\Entities\InlineQueryResult;

use PhpTelegramBot\Core\Entities\InlineKeyboardMarkup;
use PhpTelegramBot\Core\Entities\InputMessageContent\InputMessageContent;
use PhpTelegramBot\Core\Entities\MessageEntity;

/**
 * @method string                    getVideoUrl()              A valid URL for the embedded video player or video file
 * @method string                    getMimeType()              MIME type of the content of the video URL, “text/html” or “video/mp4”
 * @method string                    getThumbnailUrl()          URL of the thumbnail (JPEG only), for the video
 * @method string                    getTitle()                 Title for the result
 * @method string|null               getCaption()               Optional. Caption of the video to be sent, 0-1024 characters after entities parsing
 * @method string|null               getParseMode()             Optional. Mode for parsing entities in the video caption. See formatting options for more details.
 * @method MessageEntity[]|null      getCaptionEntities()       Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 * @method bool|null                 getShowCaptionAboveMedia() Optional. Pass True, if the caption must be shown above the message media
 * @method int|null                  getVideoWidth()            Optional. Video width
 * @method int|null                  getVideoHeigt()            Optional. Video height
 * @method int|null                  getVideoDuration()         Optional. Video duration in seconds
 * @method string|null               getDescription()           Optional. Short description of the result
 * @method InlineKeyboardMarkup|null getReplyMarkup()           Optional. Inline keyboard attached to the message
 * @method InputMessageContent|null  getInputMessageContent()   Optional. Content of the message to be sent instead of the video. This field is required if InlineQueryResultVideo is used to send an HTML-page as a result (e.g., a YouTube video).
 */
class InlineQueryResultVideo extends InlineQueryResult
{
    protected static function subEntities(): array
    {
        return [
            'caption_entities'      => [MessageEntity::class],
            'reply_markup'          => InlineKeyboardMarkup::class,
            'input_message_content' => InputMessageContent::class,
        ];
    }

    protected static function presetData(): array
    {
        return [
            'type' => 'video',
        ];
    }
}
