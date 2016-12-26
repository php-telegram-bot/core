<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Botan;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/shortener" command
 */
class ShortenerCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'shortener';
    protected $description = 'Botan Shortener example';
    protected $usage = '/shortener';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();

        $data = [];
        $data['chat_id'] = $chat_id;

        $text = Botan::shortenUrl("https://github.com/akalongman/php-telegram-bot", $user_id);

        $data['text'] = $text;

        return Request::sendMessage($data);
    }
}
