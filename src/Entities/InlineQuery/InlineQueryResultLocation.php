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
 * Class InlineQueryResultLocation
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultlocation
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'latitude'              => 36.0338,
 *   'longitude'             => 71.8601,
 *   'title'                 => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 *   'thumb_url'             => '',
 *   'thumb_width'           => 30,
 *   'thumb_height'          => 30,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be location
 * @method string               getId()                  Unique identifier for this result, 1-64 Bytes
 * @method float                getLatitude()            Location latitude in degrees
 * @method float                getLongitude()           Location longitude in degrees
 * @method string               getTitle()               Location title
 * @method InlineKeyboard       getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the location
 * @method string               getThumbUrl()            Optional. Url of the thumbnail for the result
 * @method int                  getThumbWidth()          Optional. Thumbnail width
 * @method int                  getThumbHeight()         Optional. Thumbnail height
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 Bytes
 * @method $this setLatitude(float $latitude)                                       Location latitude in degrees
 * @method $this setLongitude(float $longitude)                                     Location longitude in degrees
 * @method $this setTitle(string $title)                                            Location title
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the location
 * @method $this setThumbUrl(string $thumb_url)                                     Optional. Url of the thumbnail for the result
 * @method $this setThumbWidth(int $thumb_width)                                    Optional. Thumbnail width
 * @method $this setThumbHeight(int $thumb_height)                                  Optional. Thumbnail height
 */
class InlineQueryResultLocation extends InlineEntity
{
    /**
     * InlineQueryResultLocation constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'location';
        parent::__construct($data);
    }
}
