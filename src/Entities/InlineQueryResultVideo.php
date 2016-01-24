<?php

/*
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
    protected $message_text;
    protected $video_width;
    protected $video_height;
    protected $video_duration;

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
        $this->message_text = isset($data['message_text']) ? $data['message_text'] : null;
        if (empty($this->message_text)) {
            throw new TelegramException('message_text is empty!');
        }
        $this->video_width = isset($data['video_width']) ? $data['video_width'] : null;
        $this->video_height = isset($data['video_height']) ? $data['video_height'] : null;
        $this->video_duration = isset($data['video_duration']) ? $data['video_duration'] : null;
    }

    public function getVideoUrl()
    {
        return $this->video_url;
    }
    public function getMimeType()
    {
        return $this->mime_type;
    }
    public function getMessageText()
    {
        return $this->message_text;
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
}
