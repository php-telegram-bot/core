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

class InputTextMessageContent extends InputMessageContent
{
    /**
     * @var mixed|null
     */
    protected $message_text;

    /**
     * @var mixed|null
     */
    protected $parse_mode;

    /**
     * @var mixed|null
     */
    protected $disable_web_page_preview;

    /**
     * InputTextMessageContent constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data)
    {
        $this->message_text = isset($data['message_text']) ? $data['message_text'] : null;
        if (empty($this->message_text)) {
            throw new TelegramException('message_text is empty!');
        }

        $this->parse_mode               = isset($data['parse_mode']) ? $data['parse_mode'] : null;
        $this->disable_web_page_preview = isset($data['disable_web_page_preview']) ? $data['disable_web_page_preview'] : null;
    }
}
