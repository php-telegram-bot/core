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
 * Class InlineQueryResultVenue
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultvenue
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'latitude'              => 36.0338,
 *   'longitude'             => 71.8601,
 *   'title'                 => '',
 *   'address'               => '',
 *   'foursquare_id'         => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 *   'thumb_url'             => '',
 *   'thumb_width'           => 30,
 *   'thumb_height'          => 30,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be venue
 * @method string               getId()                  Unique identifier for this result, 1-64 Bytes
 * @method float                getLatitude()            Latitude of the venue location in degrees
 * @method float                getLongitude()           Longitude of the venue location in degrees
 * @method string               getTitle()               Title of the venue
 * @method string               getAddress()             Address of the venue
 * @method string               getFoursquareId()        Optional. Foursquare identifier of the venue if known
 * @method InlineKeyboard       getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the venue
 * @method string               getThumbUrl()            Optional. Url of the thumbnail for the result
 * @method int                  getThumbWidth()          Optional. Thumbnail width
 * @method int                  getThumbHeight()         Optional. Thumbnail height
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 Bytes
 * @method $this setLatitude(float $latitude)                                       Latitude of the venue location in degrees
 * @method $this setLongitude(float $longitude)                                     Longitude of the venue location in degrees
 * @method $this setTitle(string $title)                                            Title of the venue
 * @method $this setAddress(string $address)                                        Address of the venue
 * @method $this setFoursquareId(string $foursquare_id)                             Optional. Foursquare identifier of the venue if known
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the venue
 * @method $this setThumbUrl(string $thumb_url)                                     Optional. Url of the thumbnail for the result
 * @method $this setThumbWidth(int $thumb_width)                                    Optional. Thumbnail width
 * @method $this setThumbHeight(int $thumb_height)                                  Optional. Thumbnail height
 */
class InlineQueryResultVenue extends InlineEntity
{
    /**
     * InlineQueryResultVenue constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'venue';
        parent::__construct($data);
    }
}
