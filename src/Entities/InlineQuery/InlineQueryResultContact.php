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
 * Class InlineQueryResultContact
 *
 * @link https://core.telegram.org/bots/api#inlinequeryresultcontact
 *
 * <code>
 * $data = [
 *   'id'                    => '',
 *   'phone_number'          => '',
 *   'first_name'            => '',
 *   'last_name'             => '',
 *   'reply_markup'          => <InlineKeyboard>,
 *   'input_message_content' => <InputMessageContent>,
 *   'thumb_url'             => '',
 *   'thumb_width'           => 30,
 *   'thumb_height'          => 30,
 * ];
 * </code>
 *
 * @method string               getType()                Type of the result, must be contact
 * @method string               getId()                  Unique identifier for this result, 1-64 Bytes
 * @method string               getPhoneNumber()         Contact's phone number
 * @method string               getFirstName()           Contact's first name
 * @method string               getLastName()            Optional. Contact's last name
 * @method string               getVcard()               Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
 * @method InlineKeyboard       getReplyMarkup()         Optional. Inline keyboard attached to the message
 * @method InputMessageContent  getInputMessageContent() Optional. Content of the message to be sent instead of the contact
 * @method string               getThumbUrl()            Optional. Url of the thumbnail for the result
 * @method int                  getThumbWidth()          Optional. Thumbnail width
 * @method int                  getThumbHeight()         Optional. Thumbnail height
 *
 * @method $this setId(string $id)                                                  Unique identifier for this result, 1-64 Bytes
 * @method $this setPhoneNumber(string $phone_number)                               Contact's phone number
 * @method $this setFirstName(string $first_name)                                   Contact's first name
 * @method $this setLastName(string $last_name)                                     Optional. Contact's last name
 * @method $this setVcard(string $vcard)                                            Optional. Additional data about the contact in the form of a vCard, 0-2048 bytes
 * @method $this setReplyMarkup(InlineKeyboard $reply_markup)                       Optional. Inline keyboard attached to the message
 * @method $this setInputMessageContent(InputMessageContent $input_message_content) Optional. Content of the message to be sent instead of the contact
 * @method $this setThumbUrl(string $thumb_url)                                     Optional. Url of the thumbnail for the result
 * @method $this setThumbWidth(int $thumb_width)                                    Optional. Thumbnail width
 * @method $this setThumbHeight(int $thumb_height)                                  Optional. Thumbnail height
 */
class InlineQueryResultContact extends InlineEntity implements InlineQueryResult
{
    /**
     * InlineQueryResultContact constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'contact';
        parent::__construct($data);
    }
}
