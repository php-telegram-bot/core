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

class ChosenInlineResult extends Entity
{

    protected $result_id;
    protected $from;
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

        $this->query = isset($data['query']) ? $data['query'] : null;
        $this->offset = isset($data['offset']) ? $data['offset'] : null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function geFrom()
    {
        return $this->from;
    }
    public function getQuery()
    {
        return $this->query;
    }
}
