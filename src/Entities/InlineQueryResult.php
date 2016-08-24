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
    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed|null
     */
    protected $id;

    /**
     * @var mixed|null
     */
    protected $input_message_content;

    /**
     * @var mixed|null
     */
    protected $reply_markup;

    /**
     * InlineQueryResult constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        $this->type = null;
        $this->id   = isset($data['id']) ? $data['id'] : null;
        if (empty($this->id)) {
            throw new TelegramException('id is empty!');
        }

        $this->input_message_content = isset($data['input_message_content']) ? $data['input_message_content'] : null;
        $this->reply_markup          = isset($data['reply_markup']) ? $data['reply_markup'] : null;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * Get input message content
     *
     * @return mixed|null
     */
    public function getInputMessageContent()
    {
        return $this->input_message_content;
    }

    /**
     * Get reply markup
     *
     * @return mixed|null
     */
    public function getReplyMarkup()
    {
        return $this->reply_markup;
    }
}
