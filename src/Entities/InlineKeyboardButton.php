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

class InlineKeyboardButton extends Entity
{
    protected $text;
    protected $url;
    protected $callback_data;
    protected $switch_inline_query;
    private $hasOption = false;

    /**
     * InlineKeyboardButton constructor.
     *
     * @param array $data
     * @throws TelegramException
     */
    public function __construct($data = array())
    {
        $this->text = isset($data['text']) ? $data['text'] : null;
        if (empty($this->text)) {
            throw new TelegramException('text is empty!');
        }

        if (isset($data['url']) and !empty($data['url'])) {
            $this->url = $data['url'];
            $this->hasOption = true;
        }

        if (!$this->hasOption and (isset($data['callback_data']) and !empty($data['callback_data']))) {
            $this->callback_data = $data['callback_data'];
            $this->isSetOption = true;
        }

        if (!$this->hasOption and (isset($data['switch_inline_query']) and !empty($data['switch_inline_query']))) {
            $this->switch_inline_query = $data['switch_inline_query'];
        }

        if (!$this->url && !$this->callback_data && !$this->switch_inline_query) {
            throw new TelegramException('You must use at least one of these fields: url, callback_data, switch_inline_query!');
        }
    }
}
