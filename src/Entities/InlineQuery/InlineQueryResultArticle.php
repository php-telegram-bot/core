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
 * Class InlineQueryResultArticle
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultarticle
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'title'                 => '',
 *   'input_message_content' => <InputMessageContent>,
 *   'reply_markup'          => <InlineKeyboard>,
 *   'url'                   => '',
 *   'hide_url'              => true,
 *   'description'           => '',
 *   'thumbnail_url'         => '',
 *   'thumbnail_width'       => 30,
 *   'thumbnail_height'      => 30,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be article
 * @method string               getId()                  Unique identifier for this result, 1-64 Bytes
 * @method string               getTitle()               Title of the result
 * @method InputMessageContent  getInputMessageContent() Content of the message to be sent
 * @method InlineKeyboard       getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method string               getUrl()                 Optional. URL of the result
 * @method bool                 getHideUrl()             Optional. Pass True, if you don't want the URL to be shown in the message
 * @method string               getDescription()         Optional. Short description of the result
 * @method string               getThumbnailUrl()        Optional. Url of the thumbnail for the result
 * @method int                  getThumbnailWidth()      Optional. Thumbnail width
 * @method int                  getThumbnailHeight()     Optional. Thumbnail height
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 Bytes
 * @method $this setTitle(string $title)                                            Title of the result
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Content of the message to be sent
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. Inline keyboard attached to the message
 * @method $this setUrl(string $url)                                                Optional. URL of the result
 * @method $this setHideUrl(bool $hide_url)                                         Optional. Pass True, if you don't want the URL to be shown in the message
 * @method $this setDescription(string $description)                                Optional. Short description of the result
 * @method $this setThumbnailUrl(string $thumbnail_url)                             Optional. Url of the thumbnail for the result
 * @method $this setThumbnailWidth(int $thumbnail_width)                            Optional. Thumbnail width
 * @method $this setThumbnailHeight(int $thumbnail_height)                          Optional. Thumbnail height
 */
class InlineQueryResultArticle extends InlineEntity implements InlineQueryResult
{
    /**
     * InlineQueryResultArticle constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'article';
        parent::__construct($data);
    }
}
