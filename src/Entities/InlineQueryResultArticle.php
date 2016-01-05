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

class InlineQueryResultArticle extends Entity
{
    protected $type;
    protected $id;
    protected $title;
    protected $message_text;
    protected $parse_mode;
    protected $disable_web_page_preview;
    protected $url;
    protected $hide_url;
    protected $description;
    protected $thumb_url;
    protected $thumb_width;
    protected $thumb_height;

    public function __construct(array $data)
    {

        $this->type = 'article';

        $this->id = isset($data['id']) ? $data['id'] : null;
        if (empty($this->id)) {
            throw new TelegramException('id is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->message_text = isset($data['message_text']) ? $data['message_text'] : null;
        if (empty($this->message_text)) {
            throw new TelegramException('message_text is empty!');
        }

        $this->parse_mode = isset($data['parse_mode']) ? $data['parse_mode'] : null;
        $this->disable_web_page_preview = isset($data['disable_webpage_preview']) ? $data['disable_webpage_preview'] : null;
        $this->url = isset($data['url']) ? $data['url'] : null;
        $this->hide_url = isset($data['hide_url']) ? $data['hide_url'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        $this->thumb_width = isset($data['thumb_width']) ? $data['thumb_width'] : null;
        $this->thumb_height = isset($data['thumb_height']) ? $data['thumb_height'] : null;

    }

    public function getType()
    {
        return $this->type;
    }
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getMessageText()
    {
        return $this->message_text;
    }

    public function getParseMode()
    {
        return $this->parse_mode;
    }

    public function getDisableWebPagePreview()
    {
        return $this->disable_web_page_preview;
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
