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

class InlineQueryResultVenue extends InlineQueryResult
{
    protected $latitude;
    protected $longitude;
    protected $title;
    protected $address;
    protected $foursquare_id;
    protected $thumb_url;
    protected $thumb_width;
    protected $thumb_height;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'venue';

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

        $this->address = isset($data['address']) ? $data['address'] : null;
        if (empty($this->address)) {
            throw new TelegramException('address is empty!');
        }

        $this->foursquare_id = isset($data['foursquare_id']) ? $data['foursquare_id'] : null;

        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        $this->thumb_width = isset($data['thumb_width']) ? $data['thumb_width'] : null;
        $this->thumb_height = isset($data['thumb_height']) ? $data['thumb_height'] : null;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getFoursquareId()
    {
        return $this->foursquare_id;
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
