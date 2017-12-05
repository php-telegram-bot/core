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
 * Class InputMediaVideo
 *
 * @link https://core.telegram.org/bots/api#inputmediavideo
 *
 * <code>
 * $data = [
 *   'media'    => '123abc',
 *   'caption'  => 'Video caption',
 *   'width'    => 800,
 *   'heidht'   => 600,
 *   'duration' => 42
 * ];
 * </code>
 *
 * @method string getType()     Type of the result, must be video
 * @method string getMedia()    File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name.
 * @method string getCaption()  Optional. Caption of the video to be sent, 0-200 characters
 * @method int    getWidth()    Optional. Video width
 * @method int    getHeight()   Optional. Video height
 * @method int    getDuration() Optional. Video duration
 *
 * @method $this setMedia(string $media)     File to send. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass "attach://<file_attach_name>" to upload a new one using multipart/form-data under <file_attach_name> name.
 * @method $this setCaption(string $caption) Optional. Caption of the video to be sent, 0-200 characters
 * @method $this setWidth(int $width)        Optional. Video width
 * @method $this setHeight(int $height)      Optional. Video height
 * @method $this setDuration(int $duration)  Optional. Video duration
 */
class InputMediaVideo extends Entity implements InputMedia
{
    /**
     * InputMediaVideo constructor
     *
     * @param array $data
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $data['type'] = 'video';
        parent::__construct($data);
    }
}
