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

class InlineQueryResult extends Entity
{
    protected $type;
    protected $id;
    protected $input_message_content;
    protected $reply_markup;

    public function __construct(array $data)
    {
        $this->type = null;
        $this->id = isset($data['id']) ? $data['id'] : null;
        if (empty($this->id)) {
            throw new TelegramException('id is empty!');
        }

        $this->input_message_content = isset($data['input_message_content']) ? $data['input_message_content'] : null;
        $this->reply_markup = isset($data['reply_markup']) ? $data['reply_markup'] : null;
    }

    public function getType()
    {
        return $this->type;
    }
    public function getId()
    {
        return $this->id;
    }
}
