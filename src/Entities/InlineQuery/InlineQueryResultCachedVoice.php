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
 * Class InlineQueryResultCachedVoice
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedvoice
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'voice_file_id'         => '',
 *   'title'                 => '',
 *   'caption'               => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be voice
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getVoiceFileId()         A valid file identifier for the voice message
 * @method string               getTitle()               Voice message title
 * @method string               getCaption()             Optional. Caption, 0-200 characters
 * @method InlineKeyboard       getReplyMarkup()         Optional. An Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the voice message
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setVoiceFileId(string $voice_file_id)                              A valid file identifier for the voice message
 * @method $this setTitle(string $title)                                            Voice message title
 * @method $this setCaption(string $caption)                                        Optional. Caption, 0-200 characters
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. An Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the voice message
 */
class InlineQueryResultCachedVoice extends InlineEntity implements InlineQueryResult
{
    /**
     * InlineQueryResultCachedVoice constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'voice';
        parent::__construct($data);
    }
}
