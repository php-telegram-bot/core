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

class User extends Entity
{
    /**
     * @var mixed|null
     */
    protected $id;

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
    protected $username;

    /**
     * User constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {

        $this->id = isset($data['id']) ? $data['id'] : null;
        if (empty($this->id)) {
            throw new TelegramException('id is empty!');
        }

        $this->first_name = isset($data['first_name']) ? $data['first_name'] : null;

        $this->last_name = isset($data['last_name']) ? $data['last_name'] : null;
        $this->username  = isset($data['username']) ? $data['username'] : null;
    }

    /**
     * Get id
     *
     * @return mixed|null
     */
    public function getId()
    {
        return $this->id;
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
     * Get username
     *
     * @return mixed|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Try nebtion
     *
     * @return mixed|null|string
     */
    public function tryMention()
    {
        if (is_null($this->username)) {
            if (!is_null($this->last_name)) {
                return $this->first_name . ' ' . $this->last_name;
            }
            return $this->first_name;
        }
        return '@' . $this->username;
    }
}
