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

class InlineQueryResult extends Entity
{
    protected $type;
    protected $id;
    protected $title;
    protected $parse_mode;
    protected $disable_web_page_preview;

    public function __construct(array $data)
    {
        $this->type = null;
        $this->id = isset($data['id']) ? $data['id'] : null;
        if (empty($this->id)) {
            throw new TelegramException('id is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->disable_web_page_preview = isset($data['disable_webpage_preview']) ? $data['disable_webpage_preview'] : null;
        $this->parse_mode = isset($data['parse_mode']) ? $data['parse_mode'] : null;
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

    public function getParseMode()
    {
        return $this->parse_mode;
    }

    public function getDisableWebPagePreview()
    {
        return $this->disable_web_page_preview;
    }
}
