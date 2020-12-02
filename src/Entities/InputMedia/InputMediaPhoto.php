<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\InputMedia;

use Longman\TelegramBot\Entities\Entity;

/**
 * Class InputMediaPhoto
 *
 * @link https://core.telegram.org/bots/api#inputmediaphoto
 *
 * <code>
 * $data = [
 *   'media'      => '123abc',
 *   'caption'    => '*Photo* caption',
 *   'parse_mode' => 'markdown',
 * ];
 * </code>
 *
 * @method string          getType()            Type of the result, must be photo
 * @method string          getMedia()           File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name.
 * @method string          getCaption()         Optional. Caption of the photo to be sent, 0-200 characters
 * @method string          getParseMode()       Optional. Mode for parsing entities in the photo caption
 * @method MessageEntity[] getCaptionEntities() Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 *
 * @method $this setMedia(string $media)                     File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name.
 * @method $this setCaption(string $caption)                 Optional. Caption of the photo to be sent, 0-200 characters
 * @method $this setParseMode(string $parse_mode)            Optional. Mode for parsing entities in the photo caption
 * @method $this setCaptionEntities(array $caption_entities) Optional. List of special entities that appear in the caption, which can be specified instead of parse_mode
 */
class InputMediaPhoto extends Entity implements InputMedia
{
    /**
     * InputMediaPhoto constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'photo';
        parent::__construct($data);
    }
}
