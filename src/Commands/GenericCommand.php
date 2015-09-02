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

class GenericCommand extends Command
{
    protected $name = 'Generic';
    protected $description = 'Handle generic commands or is executed by defaul when a command is not found';
    protected $usage = '/';
    protected $version = '1.0.0';
    protected $enabled = true;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        //you can use $command as param
        $command = $message->getCommand();
 
        $data = [];
        $data['chat_id'] = $chat_id;
        $data['text'] = 'Command: '.$command.' not found.. :(';
        $result = Request::sendMessage($data);
        return $result;
    }
}
