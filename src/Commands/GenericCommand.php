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
 * Generic command
 */
class GenericCommand extends Command
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Generic';
    protected $description = 'Handles generic commands or is executed by default when a command is not found';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * Execute command
     *
     * @todo This can't be right, as it always returns "Command: xyz not found.. :("
     *
     * @return boolean
     */
    public function execute()
    {
        $message = $this->getMessage();

        //You can use $command as param
        $command = $message->getCommand();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Command: ' . $command . ' not found.. :(',
        ];

        return Request::sendMessage($data)->isOk();
    }
}
