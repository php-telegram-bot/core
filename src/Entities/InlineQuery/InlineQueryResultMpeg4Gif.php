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
 * Class InlineQueryResultMpeg4Gif
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultmpeg4gif
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'mpeg4_url'             => '',
 *   'mpeg4_width'           => 30,
 *   'mpeg4_height'          => 30,
 *   'thumb_url'             => '',
 *   'title'                 => '',
 *   'caption'               => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be mpeg4_gif
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getMpeg4Url()            A valid URL for the MP4 file. File size must not exceed 1MB
 * @method int                  getMpeg4Width()          Optional. Video width
 * @method int                  getMpeg4Height()         Optional. Video height
 * @method int                  getMpeg4Duration()       Optional. Video duration
 * @method string               getThumbUrl()            URL of the static thumbnail (jpeg or gif) for the result
 * @method string               getTitle()               Optional. Title for the result
 * @method string               getCaption()             Optional. Caption of the MPEG-4 file to be sent, 0-200 characters
 * @method InlineKeyboard       getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the video animation
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setMpeg4Url(string $mpeg4_url)                                     A valid URL for the MP4 file. File size must not exceed 1MB
 * @method $this setMpeg4Width(int $mpeg4_width)                                    Optional. Video width
 * @method $this setMpeg4Height(int $mpeg4_height)                                  Optional. Video height
 * @method $this setMpeg4Duration(int $mpeg4_duration)                              Optional. Video duration
 * @method $this setThumbUrl(string $thumb_url)                                     URL of the static thumbnail (jpeg or gif) for the result
 * @method $this setTitle(string $title)                                            Optional. Title for the result
 * @method $this setCaption(string $caption)                                        Optional. Caption of the MPEG-4 file to be sent, 0-200 characters
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the video animation
 */
class InlineQueryResultMpeg4Gif extends InlineEntity implements InlineQueryResult
{
    /**
     * InlineQueryResultMpeg4Gif constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'mpeg4_gif';
        parent::__construct($data);
    }
}
