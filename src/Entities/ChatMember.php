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

class ChatMember extends Entity
{
    /**
     * @var \Longman\TelegramBot\Entities\User
     */
    protected $user;

    /**
     * @var mixed|null
     */
    protected $status;

    /**
     * ChatMember constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        $this->user = isset($data['user']) ? $data['user'] : null;
        if (empty($this->user)) {
            throw new TelegramException('user is empty!');
        }
        $this->user = new User($data['user']);

        $this->status = isset($data['status']) ? $data['status'] : null;
        if ($this->status === '') {
            throw new TelegramException('status is empty!');
        }
    }

    /**
     * Get user
     *
     * @return \Longman\TelegramBot\Entities\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get status
     *
     * @return mixed|null
     */
    public function getStatus()
    {
        return $this->status;
    }
}
