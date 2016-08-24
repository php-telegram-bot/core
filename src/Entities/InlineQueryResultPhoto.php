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

class InlineQueryResultPhoto extends InlineQueryResult
{
    /**
     * @var mixed|null
     */
    protected $photo_url;

    /**
     * @var mixed|null
     */
    protected $photo_width;

    /**
     * @var mixed|null
     */
    protected $photo_height;

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
    protected $description;

    /**
     * @var mixed|null
     */
    protected $caption;

    /**
     * InlineQueryResultPhoto constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'photo';

        $this->photo_url = isset($data['photo_url']) ? $data['photo_url'] : null;
        if (empty($this->photo_url)) {
            throw new TelegramException('photo_url is empty!');
        }

        $this->photo_width  = isset($data['photo_width']) ? $data['photo_width'] : null;
        $this->photo_height = isset($data['photo_height']) ? $data['photo_height'] : null;

        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        if (empty($this->thumb_url)) {
            throw new TelegramException('thumb_url is empty!');
        }

        $this->title       = isset($data['title']) ? $data['title'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->caption     = isset($data['caption']) ? $data['caption'] : null;
    }

    /**
     * Get photo url
     *
     * @return mixed|null
     */
    public function getPhotoUrl()
    {
        return $this->photo_url;
    }

    /**
     * Get photo width
     *
     * @return mixed|null
     */
    public function getPhotoWidth()
    {
        return $this->photo_width;
    }

    /**
     * Get photo height
     *
     * @return mixed|null
     */
    public function getPhotoHeight()
    {
        return $this->photo_height;
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
     * Get description
     *
     * @return mixed|null
     */
    public function getDescription()
    {
        return $this->description;
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
