<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class StartCommand extends Command
{
    protected $name = 'start';
    protected $description = 'Start command';
    protected $usage = '/';
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $public = false;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $data = array();
        $data['chat_id'] = $chat_id;

        $text = "Hi there!\nType /help to see all commands!";

        $data['text'] = $text;
        $result = Request::sendMessage($data);
        return $result;
    }
}
