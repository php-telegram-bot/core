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

class InlineQueryResultVideo extends InlineQueryResult
{

    protected $video_url;
    protected $mime_type;
    protected $thumb_url;
    protected $title;
    protected $caption;
    protected $video_width;
    protected $video_height;
    protected $video_duration;
    protected $description;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'video';

        $this->video_url = isset($data['video_url']) ? $data['video_url'] : null;
        if (empty($this->video_url)) {
            throw new TelegramException('video_url is empty!');
        }
        $this->mime_type = isset($data['mime_type']) ? $data['mime_type'] : null;
        if (empty($this->mime_type)) {
            throw new TelegramException('mime_type is empty!');
        }
        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        if (empty($this->thumb_url)) {
            throw new TelegramException('thumb_url is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;
        $this->video_width = isset($data['video_width']) ? $data['video_width'] : null;
        $this->video_height = isset($data['video_height']) ? $data['video_height'] : null;
        $this->video_duration = isset($data['video_duration']) ? $data['video_duration'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
    }

    public function getVideoUrl()
    {
        return $this->video_url;
    }
    public function getMimeType()
    {
        return $this->mime_type;
    }
    public function getThumbUrl()
    {
        return $this->thumb_url;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getCaption()
    {
        return $this->caption;
    }
    public function getVideoWidth()
    {
        return $this->video_width;
    }
    public function getVideoHeight()
    {
        return $this->video_height;
    }
    public function getVideoDuration()
    {
        return $this->video_duration;
    }
    public function getDescription()
    {
        return $this->description;
    }
}
