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

class InlineQueryResultLocation extends InlineQueryResult
{
    /**
     * @var mixed|null
     */
    protected $latitude;

    /**
     * @var mixed|null
     */
    protected $longitude;

    /**
     * @var mixed|null
     */
    protected $title;

    /**
     * @var mixed|null
     */
    protected $thumb_url;

    /**
     * @var mixed|null
     */
    protected $thumb_width;

    /**
     * @var mixed|null
     */
    protected $thumb_height;

    /**
     * InlineQueryResultLocation constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'location';

        $this->latitude = isset($data['latitude']) ? $data['latitude'] : null;
        if (empty($this->latitude)) {
            throw new TelegramException('latitude is empty!');
        }

        $this->longitude = isset($data['longitude']) ? $data['longitude'] : null;
        if (empty($this->longitude)) {
            throw new TelegramException('longitude is empty!');
        }

        $this->title = isset($data['title']) ? $data['title'] : null;
        if (empty($this->title)) {
            throw new TelegramException('title is empty!');
        }

        $this->thumb_url    = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        $this->thumb_width  = isset($data['thumb_width']) ? $data['thumb_width'] : null;
        $this->thumb_height = isset($data['thumb_height']) ? $data['thumb_height'] : null;
    }

    /**
     * Get latitude
     *
     * @return mixed|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get longitude
     *
     * @return mixed|null
     */
    public function getLongitude()
    {
        return $this->longitude;
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
     * Get thumb url
     *
     * @return mixed|null
     */
    public function getThumbUrl()
    {
        return $this->thumb_url;
    }

    /**
     * Get thumb width
     *
     * @return mixed|null
     */
    public function getThumbWidth()
    {
        return $this->thumb_width;
    }

    /**
     * Get thumb height
     *
     * @return mixed|null
     */
    public function getThumbHeight()
    {
        return $this->thumb_height;
    }
}
