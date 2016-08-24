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
    /**
     * @var mixed|null
     */
    protected $id;

    /**
     * @var \Longman\TelegramBot\Entities\User
     */
    protected $from;

    /**
     * @var \Longman\TelegramBot\Entities\Location
     */
    protected $location;

    /**
     * @var mixed|null
     */
    protected $query;

    /**
     * @var mixed|null
     */
    protected $offset;

    /**
     * InlineQuery constructor.
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

        $this->from = isset($data['from']) ? $data['from'] : null;
        if (empty($this->from)) {
            throw new TelegramException('from is empty!');
        }
        $this->from = new User($this->from);

        $this->location = isset($data['location']) ? $data['location'] : null;
        if (!empty($this->location)) {
            $this->location = new Location($this->location);
        }

        $this->query  = isset($data['query']) ? $data['query'] : null;
        $this->offset = isset($data['offset']) ? $data['offset'] : null;
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
     * Get location
     *
     * @return \Longman\TelegramBot\Entities\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Get query
     *
     * @return mixed|null
     */
    public function getQuery()
    {
        return $this->query;
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
}
