<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\InlineQuery;

use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\InputMessageContent\InputMessageContent;

/**
 * Class InlineQueryResultVideo
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultvideo
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'video_url'             => '',
 *   'mime_type'             => '',
 *   'thumb_url'             => '',
 *   'title'                 => '',
 *   'caption'               => '',
 *   'video_width'           => 30,
 *   'video_height'          => 30,
 *   'video_duration'        => 123,
 *   'description'           => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be video
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getVideoUrl()            A valid URL for the embedded video player or video file
 * @method string               getMimeType()            Mime type of the content of video url, “text/html” or “video/mp4”
 * @method string               getThumbUrl()            URL of the thumbnail (jpeg only) for the video
 * @method string               getTitle()               Title for the result
 * @method string               getCaption()             Optional. Caption of the video to be sent, 0-200 characters
 * @method int                  getVideoWidth()          Optional. Video width
 * @method int                  getVideoHeight()         Optional. Video height
 * @method int                  getVideoDuration()       Optional. Video duration in seconds
 * @method string               getDescription()         Optional. Short description of the result
 * @method InlineKeyboard       getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the video
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setVideoUrl(string $video_url)                                     A valid URL for the embedded video player or video file
 * @method $this setMimeType(string $mime_type)                                     Mime type of the content of video url, “text/html” or “video/mp4”
 * @method $this setThumbUrl(string $thumb_url)                                     URL of the thumbnail (jpeg only) for the video
 * @method $this setTitle(string $title)                                            Title for the result
 * @method $this setCaption(string $caption)                                        Optional. Caption of the video to be sent, 0-200 characters
 * @method $this setVideoWidth(int $video_width)                                    Optional. Video width
 * @method $this setVideoHeight(int $video_height)                                  Optional. Video height
 * @method $this setVideoDuration(int $video_duration)                              Optional. Video duration in seconds
 * @method $this setDescription(string $description)                                Optional. Short description of the result
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the video
 */
class InlineQueryResultVideo extends InlineEntity
{
    /**
     * InlineQueryResultVideo constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'video';
        parent::__construct($data);
    }
}
