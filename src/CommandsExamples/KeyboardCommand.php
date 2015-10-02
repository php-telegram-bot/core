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

use Longman\TelegramBot\Entities\ReplyKeyboardMarkup;
use Longman\TelegramBot\Entities\ReplyKeyboardHide;
use Longman\TelegramBot\Entities\ForceReply;

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



        #Keyboard examples
        $keyboards = array();

        //0
        $keyboard[] = ['7','8','9'];
        $keyboard[] = ['4','5','6'];
        $keyboard[] = ['1','2','3'];
        $keyboard[] = [' ','0',' '];
       
        $keyboards[] = $keyboard;
        unset($keyboard);

        //1
        $keyboard[] = ['7','8','9','+'];
        $keyboard[] = ['4','5','6','-'];
        $keyboard[] = ['1','2','3','*'];
        $keyboard[] = [' ','0',' ','/'];

        $keyboards[] = $keyboard;
        unset($keyboard);


        //2
        $keyboard[] = ['A'];
        $keyboard[] = ['B'];
        $keyboard[] = ['C'];

        $keyboards[] = $keyboard;
        unset($keyboard);



        //3
        $keyboard[] = ['A'];
        $keyboard[] = ['B'];
        $keyboard[] = ['C','D'];

        $keyboards[] = $keyboard;
        unset($keyboard);


        $reply_keyboard_markup = new ReplyKeyboardMarkup(
            [
                'keyboard' => $keyboards[1] ,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'selective' => false
            ]
        );
        #echo $json;
        $data['reply_markup'] = $reply_keyboard_markup;

        $result = Request::sendMessage($data);
        return $result;
    }
}
