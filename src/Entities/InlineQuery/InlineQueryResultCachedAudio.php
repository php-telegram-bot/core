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

use Longman\TelegramBot\Entities\InlineKeyboardMarkup;
use Longman\TelegramBot\Entities\InputMessageContent\InputMessageContent;

/**
 * Class InlineQueryResultCachedAudio
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedaudio
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'audio_file_id'         => '',
 *   'reply_markup'          => <InlineKeyboardMarkup>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be audio
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getAudioFileId()         A valid file identifier for the audio file
 * @method InlineKeyboardMarkup getReplyMarkup()         Optional. An Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the audio
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setAudioFileId(string $audio_file_id)                              A valid file identifier for the audio file
 * @method $this setReplyMarkup(InlineKeyboardMarkup $reply_markup)                 Optional. Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the photo
 */
class InlineQueryResultCachedAudio extends InlineEntity
{
    /**
     * InlineQueryResultCachedAudio constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'audio';
        parent::__construct($data);
    }
}
