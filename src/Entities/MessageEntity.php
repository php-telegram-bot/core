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

class MessageEntity extends Entity
{
    /**
     * @var mixed|null
     */
    protected $type;

    /**
     * @var mixed|null
     */
    protected $offset;

    /**
     * @var mixed|null
     */
    protected $length;

    /**
     * @var mixed|null
     */
    protected $url;

    /**
     * @var \Longman\TelegramBot\Entities\User|null
     */
    protected $user;

    /**
     * MessageEntity constructor.
     *
     * @TODO check for type value from this list: https://core.telegram.org/bots/api#messageentity
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        $this->type = isset($data['type']) ? $data['type'] : null;
        if (empty($this->type)) {
            throw new TelegramException('type is empty!');
        }

        $this->offset = isset($data['offset']) ? $data['offset'] : null;
        if ($this->offset === '') {
            throw new TelegramException('offset is empty!');
        }

        $this->length = isset($data['length']) ? $data['length'] : null;
        if ($this->length === '') {
            throw new TelegramException('length is empty!');
        }

        $this->url  = isset($data['url']) ? $data['url'] : null;
        $this->user = isset($data['user']) ? new User($data['user']) : null;
    }

    /**
     * Get type
     *
     * @return mixed|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get offset
     *
     * @return mixed|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Get length
     *
     * @return mixed|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Get url
     *
     * @return mixed|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get user
     *
     * @return \Longman\TelegramBot\Entities\User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
