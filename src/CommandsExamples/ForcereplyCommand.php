<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by <marco.bore@gmail.com>
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

use Longman\TelegramBot\Entities\ReplyKeyboardMarkup;
use Longman\TelegramBot\Entities\ReplyKeyboardHide;
use Longman\TelegramBot\Entities\ForceReply;

class ForceReplyCommand extends Command
{
    protected $name = 'forcereply';
    protected $description = 'Force reply with reply markup';
    protected $usage = '/forcereply';
    protected $version = '0.0.5';
    protected $enabled = true;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();
        $message_id = $message->getMessageId();

        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);

        $data = array();
        $data['chat_id'] = $chat_id;
        $data['text'] = 'Write something:';
        #$data['reply_to_message_id'] = $message_id;

        $force_reply = new ForceReply(['selective' => false]);

        #echo $json;
        $data['reply_markup'] = $force_reply;


        $result = Request::sendMessage($data);
        return $result;
    }
}
