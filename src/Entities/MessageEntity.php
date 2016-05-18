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

class MessageEntity extends Entity
{
    protected $type;
    protected $offset;
    protected $length;
    protected $url;

    /**
     * @todo check for type value from this list: https://core.telegram.org/bots/api#messageentity
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

        $this->url = isset($data['url']) ? $data['url'] : null;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
