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

class InputLocationMessageContent extends InputMessageContent
{
    protected $latitude;
    protected $longitude;

    public function __construct(array $data)
    {
        //parent::__construct($data);

        $this->latitude isset($data['latitude']) ? $data['latitude'] : null;
        if (empty($this->latitude)) {
            throw new TelegramException('latitude is empty!');
        }

        $this->longitude isset($data['longitude']) ? $data['longitude'] : null;
        if (empty($this->longitude)) {
            throw new TelegramException('longitude is empty!');
        }
    }
}
