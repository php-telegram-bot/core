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
    /**
     * @var mixed|null
     */
    protected $text;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var mixed
     */
    protected $callback_data;

    /**
     * @var mixed
     */
    protected $switch_inline_query;

    /**
     * InlineKeyboardButton constructor.
     *
     * @param array $data
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct(array $data = [])
    {
        $this->text = isset($data['text']) ? $data['text'] : null;
        if (empty($this->text)) {
            throw new TelegramException('text is empty!');
        }

        $num_params = 0;
        foreach (['url', 'callback_data', 'switch_inline_query'] as $param) {
            if (!empty($data[$param])) {
                $this->$param = $data[$param];
                $num_params++;
            }
        }
        if ($num_params !== 1) {
            throw new TelegramException('You must use only one of these fields: url, callback_data, switch_inline_query!');
        }
    }
}
