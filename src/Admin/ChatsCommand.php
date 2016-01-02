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
use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Exception\TelegramException;

class ChatsCommand extends Command
{
    protected $name = 'chats';
    protected $description = 'List all chats stored by the bot';
    protected $usage = '/chats ';
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $public = true;
    //need Mysql
    protected $need_mysql = true;

    public function executeNoDB()
    {
        //Database not setted or without connection
        //Preparing message
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $data = [];
        $data['chat_id'] = $chat_id;
        $data['text'] =  'Sorry no database connection, unable to execute '.$this->name.' command.';
        $result = Request::sendMessage($data);
        return $result->isOk();
    }

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);

        $results = DB::selectChats(
            true, //Send to groups (group chat)
            true, //Send to supergroups (single chat)
            true, //Send to users (single chat)
            null, //'yyyy-mm-dd hh:mm:ss' date range from
            null  //'yyyy-mm-dd hh:mm:ss' date range to
        );

        $user_chats = 0;
        $group_chats = 0;
        $super_group_chats = 0;
        $text = "List of bot chats:\n";

        foreach ($results as $result) {
            //initialize a chat object
            $result['id'] =  $result['chat_id'];

            $chat = new Chat($result);

            if ($chat->isPrivateChat()) {
                $text .= '- P '.$chat->tryMention()."\n";
                ++$user_chats;
            } elseif ($chat->isGroupChat()) {
                $text .= '- G '.$chat->getTitle()."\n";
                ++$group_chats;
            } elseif ($chat->isSuperGroup()) {
                $text .= '- S '.$chat->getTitle()."\n";
                ++$super_group_chats;
            }

        }
        if (($group_chats + $user_chats + $super_group_chats) == 0) {
            $text = "No chats found..";
        } else {
            $text .= "\nPrivate Chats: ".$user_chats;
            $text .= "\nGroup: ".$group_chats;
            $text .= "\nSuper Group: ".$super_group_chats;
            $text .= "\nTot: ".($group_chats + $user_chats + $super_group_chats);
        }

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['text'] = $text;
        $result = Request::sendMessage($data);
        return $result->isOk();
    }
}
