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

class CallbackQuery extends Entity
{
    /**
     * @var mixed|null
     */
    protected $id;

    /**
     * @var \Longman\TelegramBot\Entities\User
     */
    protected $from;

    /**
     * @var \Longman\TelegramBot\Entities\Message
     */
    protected $message;

    /**
     * @var mixed|null
     */
    protected $inline_message_id;

    /**
     * @var mixed|null
     */
    protected $data;

    /**
     * CallbackQuery constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     * 
     * @todo This need refactor. Not support SOLID - "D" (need dependency inversion class User as argument)
     */
    public function __construct(array $data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        if (empty($this->id)) {
            throw new TelegramException('id is empty!');
        }

        $this->from = isset($data['from']) ? $data['from'] : null;
        if (empty($this->from)) {
            throw new TelegramException('from is empty!');
        }
        $this->from = new User($this->from);

        $this->message = isset($data['message']) ? $data['message'] : null;
        if (!empty($this->message)) {
            $this->message = new Message($this->message, $this->getBotName());
        }

        $this->inline_message_id = isset($data['inline_message_id']) ? $data['inline_message_id'] : null;

        $this->data = isset($data['data']) ? $data['data'] : null;
        if (empty($this->data)) {
            throw new TelegramException('data is empty!');
        }
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
     * Get from
     *
     * @return \Longman\TelegramBot\Entities\User
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get message
     *
     * @return \Longman\TelegramBot\Entities\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get inline message id
     *
     * @return mixed|null
     */
    public function getInlineMessageId()
    {
        return $this->inline_message_id;
    }

    /**
     * Get data
     *
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }
}
