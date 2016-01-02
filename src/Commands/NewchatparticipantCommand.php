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

class NewchatparticipantCommand extends Command
{
    protected $name = 'Newchatparticipant';
    protected $description = 'New Chat Participant';
    protected $usage = '/';
    protected $version = '1.0.0';
    protected $enabled = true;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $participant = $message->getNewChatParticipant();

        $chat_id = $message->getChat()->getId();
        $data = [];
        $data['chat_id'] = $chat_id;

        if (strtolower($participant->getUsername()) == strtolower($this->getTelegram()->getBotName())) {
            $text = 'Hi there!';
        } else {
            $text = 'Hi '.$participant->tryMention().' !';
        }

        $data['text'] = $text;
        $result = Request::sendMessage($data);
        return $result->isOk();
    }
}
