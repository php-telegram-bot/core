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
    /**
     * @var mixed|null
     */
    protected $phone_number;

    /**
     * @var mixed|null
     */
    protected $first_name;

    /**
     * @var mixed|null
     */
    protected $last_name;

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
     * InlineQueryResultContact constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
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

        $this->thumb_url    = isset($data['thumb_url']) ? $data['thumb_url'] : null;
        $this->thumb_width  = isset($data['thumb_width']) ? $data['thumb_width'] : null;
        $this->thumb_height = isset($data['thumb_height']) ? $data['thumb_height'] : null;
    }

    /**
     * Get phone number
     *
     * @return mixed|null
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Get first name
     *
     * @return mixed|null
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Get last name
     *
     * @return mixed|null
     */
    public function getLastName()
    {
        return $this->last_name;
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
