<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\ReplyKeyboardMarkup;

/**
 * User "/keyboard" command
 */
class KeyboardCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'keyboard';
    protected $description = 'Show a custom keybord with reply markup';
    protected $usage = '/keyboard';
    protected $version = '0.0.6';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['text'] = 'Press a Button:';

        //Keyboard examples
        $keyboards = [];

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

        //4  (bots 2.0)
        $keyboard[] = [
            [
                'text' => 'request_contact',
                'request_contact' => true
            ],
            [
                'text' => 'request_location',
                'request_location' => true
            ]
        ];

        $keyboards[] = $keyboard;
        unset($keyboard);

        $data['reply_markup'] = new ReplyKeyboardMarkup(
            [
                'keyboard' => $keyboards[1] ,
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'selective' => false
            ]
        );

        return Request::sendMessage($data);
    }
}
