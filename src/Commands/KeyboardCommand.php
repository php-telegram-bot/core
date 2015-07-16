<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * written by Marco Boretto <marco.bore@gmail.com>
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\ReplyMarkup;

class KeyboardCommand extends Command
{
    protected $name = 'keyboard';
    protected $description = 'Show a custom keybord with reply markup';
    protected $usage = '/keyboard';
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
        $data['text'] = 'Press a Button:';
        #$data['reply_to_message_id'] = $message_id;


        $markup = new ReplyMarkup;

        //$markup->addKeyBoard($options,$resize=false,$once=false,$selective=false)
        //onother keyboard example
        #$markup->addKeyBoard(array('A','B','C'),true,false,false);
        //onother keyboard example
        #$markup->addKeyBoard(array('A',array('B','B1'),'C'),true,false,false);



        $markup->addKeyBoard(array(
            array('7','8','9'),
            array('4','5','6'),
            array('1','2','3'),
            array('0')
            ), true, false, false);

        $json = $markup->getJsonQuery();
        $data['reply_markup'] = $json;

        $result = Request::sendMessage($data);
        return $result;
    }
}
