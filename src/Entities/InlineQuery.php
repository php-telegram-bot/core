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

class InlineQuery extends Entity
{

    protected $id;
    protected $from;
    protected $location;
    protected $query;
    protected $offset;

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

        $this->location = isset($data['location']) ? $data['location'] : null;
        if (!empty($this->location)) {
            $this->location = new Location($this->location);
        }

        $this->query = isset($data['query']) ? $data['query'] : null;
        $this->offset = isset($data['offset']) ? $data['offset'] : null;
    }

    public function getId()
    {
        return $this->id;
    }
    public function getFrom()
    {
        return $this->from;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function getQuery()
    {
        return $this->query;
    }
    public function getOffset()
    {
        return $this->offset;
    }
}
