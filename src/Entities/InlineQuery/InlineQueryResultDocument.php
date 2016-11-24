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
 * Class InlineQueryResultDocument
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultdocument
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'title'                 => '',
 *   'caption'               => '',
 *   'document_url'          => '',
 *   'mime_type'             => '',
 *   'description'           => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 *   'thumb_url'             => '',
 *   'thumb_width'           => 30,
 *   'thumb_height'          => 30,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be document
 * @method string               getId()                  Unique identifier for this result, 1-64 bytes
 * @method string               getTitle()               Title for the result
 * @method string               getCaption()             Optional. Caption of the document to be sent, 0-200 characters
 * @method string               getDocumentUrl()         A valid URL for the file
 * @method string               getMimeType()            Mime type of the content of the file, either “application/pdf” or “application/zip”
 * @method string               getDescription()         Optional. Short description of the result
 * @method InlineKeyboard       getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the file
 * @method string               getThumbUrl()            Optional. URL of the thumbnail (jpeg only) for the file
 * @method int                  getThumbWidth()          Optional. Thumbnail width
 * @method int                  getThumbHeight()         Optional. Thumbnail height
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 bytes
 * @method $this setTitle(string $title)                                            Title for the result
 * @method $this setCaption(string $caption)                                        Optional. Caption of the document to be sent, 0-200 characters
 * @method $this setDocumentUrl(string $document_url)                               A valid URL for the file
 * @method $this setMimeType(string $mime_type)                                     Mime type of the content of the file, either “application/pdf” or “application/zip”
 * @method $this setDescription(string $description)                                Optional. Short description of the result
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the file
 * @method $this setThumbUrl(string $thumb_url)                                     Optional. URL of the thumbnail (jpeg only) for the file
 * @method $this setThumbWidth(int $thumb_width)                                    Optional. Thumbnail width
 * @method $this setThumbHeight(int $thumb_height)                                  Optional. Thumbnail height
 */
class InlineQueryResultDocument extends InlineEntity
{
    /**
     * InlineQueryResultDocument constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'document';
        parent::__construct($data);
    }
}
