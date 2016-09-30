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

class Document extends Entity
{
    /**
     * @var mixed|null
     */
    protected $file_id;

    /**
     * @var \Longman\TelegramBot\Entities\PhotoSize
     */
    protected $thumb;

    /**
     * @var mixed|null
     */
    protected $file_name;

    /**
     * @var mixed|null
     */
    protected $mime_type;

    /**
     * @var mixed|null
     */
    protected $file_size;

    /**
     * Document constructor.
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

        $this->thumb = isset($data['thumb']) ? $data['thumb'] : null;
        if (!empty($this->thumb)) {
            $this->thumb = new PhotoSize($this->thumb);
        }

        $this->file_name = isset($data['file_name']) ? $data['file_name'] : null;
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
     * Get thumb
     *
     * @return \Longman\TelegramBot\Entities\PhotoSize
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * Get file name
     *
     * @return mixed|null
     */
    public function getFileName()
    {
        return $this->file_name;
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
