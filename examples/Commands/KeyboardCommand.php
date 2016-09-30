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
    protected $description = 'Show a custom keyboard with reply markup';
    protected $usage = '/keyboard';
    protected $version = '0.1.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Press a Button:',
        ];

        //Keyboard examples
        $keyboards = [];

        //Example 0
        $keyboard    = [];
        $keyboard[]  = ['7', '8', '9'];
        $keyboard[]  = ['4', '5', '6'];
        $keyboard[]  = ['1', '2', '3'];
        $keyboard[]  = [' ', '0', ' '];
        $keyboards[] = $keyboard;

        //Example 1
        $keyboard    = [];
        $keyboard[]  = ['7', '8', '9', '+'];
        $keyboard[]  = ['4', '5', '6', '-'];
        $keyboard[]  = ['1', '2', '3', '*'];
        $keyboard[]  = [' ', '0', ' ', '/'];
        $keyboards[] = $keyboard;

        //Example 2
        $keyboard    = [];
        $keyboard[]  = ['A'];
        $keyboard[]  = ['B'];
        $keyboard[]  = ['C'];
        $keyboards[] = $keyboard;

        //Example 3
        $keyboard    = [];
        $keyboard[]  = ['A'];
        $keyboard[]  = ['B'];
        $keyboard[]  = ['C', 'D'];
        $keyboards[] = $keyboard;

        //Example 4 (bots version 2.0)
        $keyboard    = [];
        $keyboard[]  = [
            [
                'text'            => 'Send my contact',
                'request_contact' => true,
            ],
            [
                'text'             => 'Send my location',
                'request_location' => true,
            ],
        ];
        $keyboards[] = $keyboard;

        //Return a random keyboard.
        $keyboard             = $keyboards[mt_rand(0, count($keyboards) - 1)];
        $data['reply_markup'] = new ReplyKeyboardMarkup(
            [
                'keyboard'          => $keyboard,
                'resize_keyboard'   => true,
                'one_time_keyboard' => false,
                'selective'         => false,
            ]
        );

        return Request::sendMessage($data);
    }
}
