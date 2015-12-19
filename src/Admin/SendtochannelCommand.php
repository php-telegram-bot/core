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
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;

class SendtochannelCommand extends Command
{
    protected $name = 'sendtochannel';
    protected $description = 'Send a message to a channel';
    protected $usage = '/sendchannel <message to send>';
    protected $version = '0.1.0';
    protected $enabled = true;
    protected $public = true;
    //need Mysql
    protected $need_mysql = false;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);
        $your_channel = '@yourchannel';
        if (empty($text)) {
            $text_back = 'Write the message to sent: /sendtochannel <message>';
        } else {
            //Send message to channel
            $data = [];
            $data['chat_id'] = $your_channel;
            $data['text'] = $text;

            $result = Request::sendMessage($data);
            if ($result->isOk()) {
                $text_back = 'Message sent succesfully to: '.$your_channel;
            } else {
                $text_back = 'Sorry message not sent to: '.$your_channel;
            }
        }

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['text'] = $text_back;

        $result = Request::sendMessage($data);
        return $result;
    }
}
