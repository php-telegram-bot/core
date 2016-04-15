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

class InlineQueryResultContact extends InlineQueryResult
{
    protected $phone_number;
    protected $first_name;
    protected $last_name;
    protected $thumb_url;
    protected $thumb_width;
    protected $thumb_height;

    public function __construct(array $data)
    {
        parent::__construct($data);

        $this->type = 'contact';

        $this->phone_number = isset($data['phone_number']) ? $data['phone_number'] : null;
        if (empty($this->phone_number)) {
            throw new TelegramException('phone_number is empty!');
        }

        $this->first_name = isset($data['first_name']) ? $data['first_name'] : null;
        if (empty($this->first_name)) {
            throw new TelegramException('first_name is empty!');
        }

        $this->last_name = isset($data['last_name']) ? $data['last_name'] : null;

        $this->thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        $this->thumb_width = isset($data['thumb_width']) ? $data['thumb_width'] : null;
        $this->thumb_height = isset($data['thumb_height']) ? $data['thumb_height'] : null;
    }

    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
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
