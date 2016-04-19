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

class ChosenInlineResult extends Entity
{

    protected $result_id;
    protected $from;
    protected $location;
    protected $inline_message_id;
    protected $query;

    public function __construct(array $data)
    {

        $this->result_id = isset($data['result_id']) ? $data['result_id'] : null;
        if (empty($this->result_id)) {
            throw new TelegramException('result_id is empty!');
        }

        $this->from = isset($data['from']) ? $data['from'] : null;
        if (empty($this->from)) {
            throw new TelegramException('from is empty!');
        }
        $this->from = new User($this->from);

        $this->location = isset($data['location']) ? $data['location'] : null;
        if (!empty($this->location)) {
            $this->location = new Location($this->location);
        }

        $this->inline_message_id = isset($data['inline_message_id']) ? $data['inline_message_id'] : null;
        $this->query = isset($data['query']) ? $data['query'] : null;
    }

    public function getResultId()
    {
        return $this->result_id;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getInlineMessageId()
    {
        return $this->inline_message_id;
    }

    public function getQuery()
    {
        return $this->query;
    }
}
