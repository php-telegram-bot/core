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

class Location extends Entity
{

    protected $longitude;
    protected $latitude;

    public function __construct(array $data)
    {

        $this->longitude = isset($data['longitude']) ? $data['longitude'] : null;
        if (empty($this->longitude)) {
            throw new TelegramException('longitude is empty!');
        }

        $this->latitude = isset($data['latitude']) ? $data['latitude'] : null;
        if (empty($this->latitude)) {
            throw new TelegramException('latitude is empty!');
        }

    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }
}
