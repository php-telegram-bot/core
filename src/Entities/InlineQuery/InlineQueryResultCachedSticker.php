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
 * Class InlineQueryResultCachedSticker
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcachedsticker
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'sticker_file_id'       => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be sticker
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getStickerFileId()       A valid file identifier of the sticker
 * @method InlineKeyboard       getReplyMarkup()         Optional. An Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the sticker
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setStickerFileId(string $sticker_file_id)                          A valid file identifier of the sticker
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. An Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the sticker
 */
class InlineQueryResultCachedSticker extends InlineEntity implements InlineQueryResult
{
    /**
     * InlineQueryResultCachedSticker constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'sticker';
        parent::__construct($data);
    }
}
