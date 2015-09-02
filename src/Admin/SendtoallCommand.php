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
use Longman\TelegramBot\Exception\TelegramException;

class SendtoallCommand extends Command
{
    protected $name = 'sendall';
    protected $description = 'Send the message to all the user\'s bot';
    protected $usage = '/sendall <message to send>';
    protected $version = '1.2.0';
    protected $enabled = true;
    protected $public = true;


    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);

        if (empty($text)) {
            $text = 'Write te message to sent: /sendall <message>';
        } else {
            $results = $this->telegram->sendToActiveChats(
                'sendMessage', //callback function to execute (see Request.php methods)
                array('text'=> $text), //Param to evaluate the request
                true, //Send to chats (group chat)
                true, //Send to users (single chat)
                null, //'yyyy-mm-dd hh:mm:ss' date range from
                null  //'yyyy-mm-dd hh:mm:ss' date range to
            );

            $tot = 0;
            $fail = 0;

            $text = "Message sended to:\n";
            foreach ($results as $result) {
                $status = '';
                $type = '';
                print_r($result);
                if ($result->isOk()) {
                    $status = '✔️';

                    $ServerResponse = $result->getResult();
                    $chat = $ServerResponse->getChat();
                    if ($chat->isSingleChat()) {
                        $name = $chat->getFirstName();
                        $type = 'user';
                    } else {
                        $name = $chat->getTitle();
                        $type = 'chat';
                    }
                } else {
                    $status = '✖️';
                    ++$fail;
                }
                ++$tot;

                $text .= $tot.') '.$status.' '.$type.' '.$name."\n";
            }
            $text .= "Delivered: ".($tot - $fail).'/'.$tot."\n";
        }
        if ($tot == 0) {
            $text = "No users or chats found..";
        }

        $data = array();
        $data['chat_id'] = $chat_id;
        //$data['reply_to_message_id'] = $message_id;
        $data['text'] = $text;

        $result = Request::sendMessage($data);
        return $result;
    }
}
