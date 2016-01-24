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

class InlineQueryResultArticle extends InlineQueryResult
{
    protected $message_text;
    protected $url;
    protected $hide_url;
    protected $description;
    protected $thumb_url;
    protected $thumb_width;
    protected $thumb_height;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'article';

        $this->message_text = isset($data['message_text']) ? $data['message_text'] : null;
        if (empty($this->message_text)) {
            throw new TelegramException('message_text is empty!');
        }

        $this->url = isset($data['url']) ? $data['url'] : null;
        $this->hide_url = isset($data['hide_url']) ? $data['hide_url'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        $this->thumb_width = isset($data['thumb_width']) ? $data['thumb_width'] : null;
        $this->thumb_height = isset($data['thumb_height']) ? $data['thumb_height'] : null;

    }

    public function getMessageText()
    {
        return $this->message_text;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getHideUrl()
    {
        return $this->hide_url;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getThumbUrl()
    {
        return $this->thumb_url;
    }

    public function getThumbWidth()
    {
        return $this->thumb_width;
    }

    public function getThumbHeight()
    {
        return $this->thumb_height;
    }
}
