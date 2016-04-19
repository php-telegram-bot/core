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
    protected $file_id;
    protected $thumb;
    protected $file_name;
    protected $mime_type;
    protected $file_size;

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

    public function getFileId()
    {
        return $this->file_id;
    }

    public function getThumb()
    {
          return $this->thumb;
    }

    public function getFileName()
    {
         return $this->file_name;
    }

    public function getMimeType()
    {
         return $this->mime_type;
    }

    public function getFileSize()
    {
         return $this->file_size;
    }
}
