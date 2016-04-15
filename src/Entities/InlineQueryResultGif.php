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

class InlineQueryResultGif extends InlineQueryResult
{

    protected $gif_url;
    protected $gif_width;
    protected $gif_height;
    protected $thumb_url;
    protected $title;
    protected $caption;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'gif';

        $this->gif_url = isset($data['gif_url']) ? $data['gif_url'] : null;
        if (empty($this->gif_url)) {
            throw new TelegramException('gif_url is empty!');
        }

        $this->gif_width = isset($data['gif_width']) ? $data['gif_width'] : null;
        $this->gif_height = isset($data['gif_height']) ? $data['gif_height'] : null;

        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        if (empty($this->thumb_url)) {
            throw new TelegramException('thumb_url is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;

    }

    public function getGifUrl()
    {
        return $this->gif_url;
    }
    public function getGifWidth()
    {
        return $this->gif_width;
    }
    public function getGifHeight()
    {
        return $this->gif_height;
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
}
