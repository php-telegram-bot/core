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

class File extends Entity
{
    protected $file_id;
    protected $file_size;
    protected $file_path;

    public function __construct(array $data)
    {

        $this->file_id = isset($data['file_id']) ? $data['file_id'] : null;
        if (empty($this->file_id)) {
            throw new TelegramException('file_id is empty!');
        }

        $this->file_size = isset($data['file_size']) ? $data['file_size'] : null;

        $this->file_path = isset($data['file_path']) ? $data['file_path'] : null;

    }

    public function getFileId()
    {
        return $this->file_id;
    }

    public function getFileSize()
    {
         return $this->file_size;
    }

    public function getFilePath()
    {
         return $this->file_path;
    }
}
