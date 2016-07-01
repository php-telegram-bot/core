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

class InlineQueryResultCachedPhoto extends InlineQueryResult
{
    protected $photo_file_id;
    protected $title;
    protected $description;
    protected $caption;

    /**
     * InlineQueryResultCachedPhoto constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'photo';

        $this->photo_file_id = isset($data['photo_file_id']) ? $data['photo_file_id'] : null;
        if (empty($this->photo_file_id)) {
            throw new TelegramException('photo_file_id is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;
    }

    public function getPhotoFileId()
    {
        return $this->photo_file_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCaption()
    {
        return $this->caption;
    }
}
