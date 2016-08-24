<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class Video extends Entity
{
    /**
     * @var mixed|null
     */
    protected $file_id;

    /**
     * @var mixed|null
     */
    protected $width;

    /**
     * @var mixed|null
     */
    protected $height;

    /**
     * @var mixed|null
     */
    protected $duration;

    /**
     * @var \Longman\TelegramBot\Entities\PhotoSize
     */
    protected $thumb;

    /**
     * @var mixed|null
     */
    protected $mime_type;

    /**
     * @var mixed|null
     */
    protected $file_size;

    /**
     * Video constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {

        $this->file_id = isset($data['file_id']) ? $data['file_id'] : null;
        if (empty($this->file_id)) {
            throw new TelegramException('file_id is empty!');
        }

        $this->width = isset($data['width']) ? $data['width'] : null;
        if (empty($this->width)) {
            throw new TelegramException('width is empty!');
        }

        $this->height = isset($data['height']) ? $data['height'] : null;
        if (empty($this->height)) {
            throw new TelegramException('height is empty!');
        }
        $this->duration = isset($data['duration']) ? $data['duration'] : null;
        if ($this->duration === '' || $this->duration === null) {
            throw new TelegramException('duration is empty!');
        }
        $this->thumb = isset($data['thumb']) ? $data['thumb'] : null;
        if (empty($this->thumb)) {
            throw new TelegramException('thumb is empty!');
        }
        $this->thumb = new PhotoSize($this->thumb);

        $this->mime_type = isset($data['mime_type']) ? $data['mime_type'] : null;
        $this->file_size = isset($data['file_size']) ? $data['file_size'] : null;
    }

    /**
     * Get file id
     *
     * @return mixed|null
     */
    public function getFileId()
    {
        return $this->file_id;
    }

    /**
     * Get width
     *
     * @return mixed|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get height
     *
     * @return mixed|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Get duration
     *
     * @return mixed|null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Get thumb
     *
     * @return \Longman\TelegramBot\Entities\PhotoSize
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * Get mime type
     *
     * @return mixed|null
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * Get file size
     *
     * @return mixed|null
     */
    public function getFileSize()
    {
        return $this->file_size;
    }
}
