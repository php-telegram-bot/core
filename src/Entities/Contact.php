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

class Contact extends Entity
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
    protected $user_id;

    /**
     * Contact constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        $this->phone_number = isset($data['phone_number']) ? $data['phone_number'] : null;
        if (empty($this->phone_number)) {
            throw new TelegramException('phone_number is empty!');
        }

        $this->first_name = isset($data['first_name']) ? $data['first_name'] : null;
        if (empty($this->first_name)) {
            throw new TelegramException('first_name is empty!');
        }

        $this->last_name = isset($data['last_name']) ? $data['last_name'] : null;
        $this->user_id   = isset($data['user_id']) ? $data['user_id'] : null;
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
     * Get user id
     *
     * @return mixed|null
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
