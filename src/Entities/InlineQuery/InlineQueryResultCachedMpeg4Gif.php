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
 * Class InlineQueryResultCachedMpeg4Gif
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedmpeg4gif
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'mpeg4_file_id'         => '',
 *   'title'                 => '',
 *   'caption'               => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be mpeg4_gif
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getMpeg4FileId()         A valid file identifier for the MP4 file
 * @method string               getTitle()               Optional. Title for the result
 * @method string               getCaption()             Optional. Caption of the MPEG-4 file to be sent, 0-200 characters
 * @method InlineKeyboard       getReplyMarkup()         Optional. An Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the video animation
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setMpeg4FileId(string $mpeg4_file_id)                              A valid file identifier for the MP4 file
 * @method $this setTitle(string $title)                                            Optional. Title for the result
 * @method $this setCaption(string $caption)                                        Optional. Caption of the MPEG-4 file to be sent, 0-200 characters
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. An Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the video animation
 */
class InlineQueryResultCachedMpeg4Gif extends InlineEntity implements InlineQueryResult
{
    /**
     * InlineQueryResultCachedMpeg4Gif constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'mpeg4_gif';
        parent::__construct($data);
    }
}
