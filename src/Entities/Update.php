<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class Update extends Entity
{

    protected $update_id;
    protected $message;

    public function __construct(array $data, $bot_name, $let_update_id_empty = 0)
    {

        $update_id = isset($data['update_id']) ? $data['update_id'] : null;

        $message = isset($data['message']) ? $data['message'] : null;

        if (empty($update_id) && !$let_update_id_empty) {
            throw new TelegramException('update_id is empty!');
        }

        $this->bot_name = $bot_name;
        $this->update_id = $update_id;
        $this->message = new Message($message, $bot_name);
    }

    public function getUpdateId()
    {

        return $this->update_id;
    }

    public function getMessage()
    {

        return $this->message;
    }
}
