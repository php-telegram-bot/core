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
 * Class InlineQueryResultAudio
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultaudio
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'audio_url'             => '',
 *   'title'                 => '',
 *   'performer'             => '',
 *   'audio_duration'        => 123,
 *   'reply_markup'          => <InlineKeyboardMarkup>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be audio
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getAudioUrl()            A valid URL for the audio file
 * @method string               getTitle()               Title
 * @method string               getPerformer()           Optional. Performer
 * @method int                  getAudioDuration()       Optional. Audio duration in seconds
 * @method InlineKeyboardMarkup getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the audio
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setAudioUrl(string $audio_url)                                     A valid URL for the audio file
 * @method $this setTitle(string $title)                                            Title
 * @method $this setPerformer(string $performer)                                    Optional. Performer
 * @method $this setAudioDuration(int $audio_duration)                              Optional. Audio duration in seconds
 * @method $this setReplyMarkup(InlineKeyboardMarkup $reply_markup)                 Optional. Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the photo
 */
class InlineQueryResultAudio extends InlineEntity
{
    /**
     * InlineQueryResultAudio constructor
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
