<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
*/
namespace Longman\TelegramBot\Commands;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class SlapCommand extends Command
{
    protected $name = 'slap';
    protected $description = 'Slap someone with their username';
    protected $usage = '/slap <@user>';
    protected $version = '1.0.0';
    protected $enabled = true;
    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);
        
        $sender='@'.$message->getFrom()->getUsername();

        //username validation
        $test=preg_match('/@[\w_]{5,}/',$text);
        if($test===0) return false;

        $data = array();
        $data['chat_id'] = $chat_id;
        $data['text'] = $sender.' slaps '.$text.' around a bit with a large trout';
        $result = Request::sendMessage($data);
        return $result;
    }
}
