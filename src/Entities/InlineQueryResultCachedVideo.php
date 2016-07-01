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

class InlineQueryResultCachedVideo extends InlineQueryResult
{
    protected $video_file_id;
    protected $title;
    protected $description;
    protected $caption;

    /**
     * InlineQueryResultCachedVideo constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'photo';

        $this->video_file_id = isset($data['video_file_id']) ? $data['video_file_id'] : null;
        if (empty($this->video_file_id)) {
            throw new TelegramException('video_file_id is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;
    }

    public function getVideoFileId()
    {
        return $this->video_file_id;
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
