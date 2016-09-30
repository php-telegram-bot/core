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

class PhotoSize extends Entity
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
    protected $file_size;

    /**
     * PhotoSize constructor.
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
     * Get file size
     *
     * @return mixed|null
     */
    public function getFileSize()
    {
        return $this->file_size;
    }
}
