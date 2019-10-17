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
 * Class InlineQueryResultCachedDocument
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcacheddocument
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'title'                 => '',
 *   'document_file_id'      => '',
 *   'description'           => '',
 *   'caption'               => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be document
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getTitle()               Title for the result
 * @method string               getDocumentFileId()      A valid file identifier for the file
 * @method string               getDescription()         Optional. Short description of the result
 * @method string               getCaption()             Optional. Caption of the document to be sent, 0-200 characters
 * @method InlineKeyboard       getReplyMarkup()         Optional. An Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the file
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setTitle(string $title)                                            Title for the result
 * @method $this setDocumentFileId(string $document_file_id)                        A valid file identifier for the file
 * @method $this setDescription(string $description)                                Optional. Short description of the result
 * @method $this setCaption(string $caption)                                        Optional. Caption of the document to be sent, 0-200 characters
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. An Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the file
 */
class InlineQueryResultCachedDocument extends InlineEntity implements InlineQueryResult
{
    /**
     * InlineQueryResultCachedDocument constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'document';
        parent::__construct($data);
    }
}
