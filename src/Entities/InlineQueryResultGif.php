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
    /**
     * @var mixed|null
     */
    protected $gif_url;

    /**
     * @var mixed|null
     */
    protected $gif_width;

    /**
     * @var mixed|null
     */
    protected $gif_height;

    /**
     * @var mixed|null
     */
    protected $thumb_url;

    /**
     * @var mixed|null
     */
    protected $title;

    /**
     * @var mixed|null
     */
    protected $caption;

    /**
     * InlineQueryResultGif constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'gif';

        $this->gif_url = isset($data['gif_url']) ? $data['gif_url'] : null;
        if (empty($this->gif_url)) {
            throw new TelegramException('gif_url is empty!');
        }

        $this->gif_width  = isset($data['gif_width']) ? $data['gif_width'] : null;
        $this->gif_height = isset($data['gif_height']) ? $data['gif_height'] : null;

        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        if (empty($this->thumb_url)) {
            throw new TelegramException('thumb_url is empty!');
        }

        $this->title   = isset($data['title']) ? $data['title'] : null;
        $this->caption = isset($data['caption']) ? $data['caption'] : null;
    }

    /**
     * Get gif url
     *
     * @return mixed|null
     */
    public function getGifUrl()
    {
        return $this->gif_url;
    }

    /**
     * Get gif width
     *
     * @return mixed|null
     */
    public function getGifWidth()
    {
        return $this->gif_width;
    }

    /**
     * Get gif height
     *
     * @return mixed|null
     */
    public function getGifHeight()
    {
        return $this->gif_height;
    }

    /**
     * Get thumb url
     *
     * @return mixed|null
     */
    public function getThumbUrl()
    {
        return $this->thumb_url;
    }

    /**
     * Get title
     *
     * @return mixed|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get caption
     *
     * @return mixed|null
     */
    public function getCaption()
    {
        return $this->caption;
    }
}
