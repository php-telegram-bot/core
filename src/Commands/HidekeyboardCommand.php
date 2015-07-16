<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by Marco Boretto <marco.bore@gmail.com>
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\ReplyMarkup;

class HidekeyboardCommand extends Command
{
    protected $name = 'hidekeyboard';
    protected $description = 'Hide the custom keyboard';
    protected $usage = '/hidekeyboard';
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
        $data['text'] = 'Keyboard Hided';
        #$data['reply_to_message_id'] = $message_id;

        $markup = new ReplyMarkup;
        //$markup->addKeyBoardHide($selective = false){
        $markup->addKeyBoardHide(false);
        $json = $markup->getJsonQuery();
        $data['reply_markup'] = $json;

        $result = Request::sendMessage($data);
        return $result;
    }
}
