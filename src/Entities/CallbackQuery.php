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
    protected $id;
    protected $from;
    protected $message;
    protected $inline_message_id;
    protected $data;

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

    public function getId()
    {
        return $this->id;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getInlineMessageId()
    {
        return $this->inline_message_id;
    }

    public function getData()
    {
        return $this->data;
    }
}
