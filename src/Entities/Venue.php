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

class Venue extends Entity
{
    protected $location;
    protected $latitude;
    protected $longitude;
    protected $title;
    protected $address;
    protected $foursquare_id;

    public function __construct(array $data)
    {
        $this->location = isset($data['location']) ? $data['location'] : null;
        $this->latitude = isset($data['latitude']) ? $data['latitude'] : null;
        $this->longitude = isset($data['longitude']) ? $data['longitude'] : null;

        // Venue can either contain location object or just latitude and longitude fields, check accordingly
        if (!empty($this->location)) {
            $this->location = new Location($this->location);
        } else {
            if (empty($this->latitude)) {
                throw new TelegramException('latitude is empty!');
            }

            if (empty($this->longitude)) {
                throw new TelegramException('longitude is empty!');
            }
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
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
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
}
