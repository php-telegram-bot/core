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

class Chat extends Entity
{
    /**
     * @var mixed|null
     */
    protected $id;
    /**
     * @var null
     */
    protected $type;

    /**
     * @var mixed|null
     */
    protected $title;

    /**
     * @var mixed|null
     */
    protected $username;

    /**
     * @var mixed|null
     */
    protected $first_name;

    /**
     * @var mixed|null
     */
    protected $last_name;

    /**
     * Chat constructor.
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

        if (isset($data['type'])) {
            $this->type = $data['type'];
        } else {
            if ($this->id > 0) {
                $this->type = 'private';
            } elseif ($this->id < 0) {
                $this->type = 'group';
            } else {
                $this->type = null;
            }
        }

        $this->title      = isset($data['title']) ? $data['title'] : null;
        $this->first_name = isset($data['first_name']) ? $data['first_name'] : null;
        $this->last_name  = isset($data['last_name']) ? $data['last_name'] : null;
        $this->username   = isset($data['username']) ? $data['username'] : null;
    }

    /**
     * Check if is group chat
     *
     * @return bool
     */
    public function isGroupChat()
    {
        if ($this->type == 'group' || $this->id < 0) {
            return true;
        }
        return false;
    }

    /**
     * Check if is private chat
     *
     * @return bool
     */
    public function isPrivateChat()
    {
        if ($this->type == 'private') {
            return true;
        }
        return false;
    }

    /**
     * Check if is super group
     *
     * @return bool
     */
    public function isSuperGroup()
    {
        if ($this->type == 'supergroup') {
            return true;
        }
        return false;
    }

    /**
     * Check if is channel
     *
     * @return bool
     */
    public function isChannel()
    {
        if ($this->type == 'channel') {
            return true;
        }
        return false;
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
     * Get type
     *
     * @return null
     */
    public function getType()
    {
        return $this->type;
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
     * Try mention
     *
     * @return mixed|null|string
     */
    public function tryMention()
    {
        if ($this->isPrivateChat()) {
            if (is_null($this->username)) {
                if (!is_null($this->last_name)) {
                    return $this->first_name . ' ' . $this->last_name;
                }
                return $this->first_name;
            }
            return '@' . $this->username;
        }
        return $this->getTitle();
    }
}
