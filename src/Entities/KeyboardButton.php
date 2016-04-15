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

class KeyboardButton extends Entity
{
    protected $text;
    protected $request_contact;
    protected $request_location;

    public function __construct($data = array())
    {
        $this->text = isset($data['text']) ? $data['text'] : null;
        if (empty($this->text)) {
            throw new TelegramException('text is empty!');
        }

        $this->request_contact = isset($data['request_contact']) ? $data['request_contact'] : null;
        $this->request_location = isset($data['request_location']) ? $data['request_location'] : null;
    }
}
