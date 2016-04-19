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

class InlineQueryResultMpeg4Gif extends InlineQueryResult
{

    protected $mpeg4_url;
    protected $mpeg4_width;
    protected $mpeg4_height;
    protected $thumb_url;
    protected $title;
    protected $caption;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'mpeg4_gif';

        $this->mpeg4_url = isset($data['mpeg4_url']) ? $data['mpeg4_url'] : null;
        if (empty($this->mpeg4_url)) {
            throw new TelegramException('mpeg4_url is empty!');
        }

        $this->mpeg4_width = isset($data['mpeg4_width']) ? $data['mpeg4_width'] : null;
        $this->mpeg4_height = isset($data['mpeg4_height']) ? $data['mpeg4_height'] : null;

        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        if (empty($this->thumb_url)) {
            throw new TelegramException('thumb_url is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;

    }

    public function getMpeg4Url()
    {
        return $this->mpeg4_url;
    }
    public function getMpeg4Width()
    {
        return $this->mpeg4_width;
    }
    public function getMpeg4Height()
    {
        return $this->mpeg4_height;
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
