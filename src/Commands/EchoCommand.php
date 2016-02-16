<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Command;
use Longman\TelegramBot\Request;

/**
 * User "/echo" command
 */
class EchoCommand extends Command
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'echo';
    protected $description = 'Show text';
    protected $usage = '/echo <text>';
    protected $version = '1.0.1';
    protected $public = true;
    /**#@-*/

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data)->isOk();
    }
}
