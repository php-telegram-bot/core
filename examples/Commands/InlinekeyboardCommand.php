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
use Longman\TelegramBot\Entities\InlineKeyboardMarkup;

/**
 * User "/inlinekeyboard" command
 */
class InlinekeyboardCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'inlinekeyboard';
    protected $description = 'Show a custom inline keybord with reply markup';
    protected $usage = '/inlinekeyboard';
    protected $version = '0.0.1';
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
        $inline_keyboards = [];

        //0
        $inline_keyboard[] = [
            [
                'text' => '<',
                'callback_data' => 'go_left'
            ],
            [
                'text' => '^',
                'callback_data' => 'go_up'
            ],
            [
                'text' => '>',
                'callback_data' => 'go_right'
            ]
        ];

        $inline_keyboards[] = $inline_keyboard;
        unset($inline_keyboard);

        //1
        $inline_keyboard[] = [
            [
                'text' => 'open google.com',
                'url' => 'google.com'
            ],
            [
                'text' => 'open youtube.com',
                'url' => 'youtube.com'
            ]
        ];

        $inline_keyboards[] = $inline_keyboard;
        unset($inline_keyboard);

        //2
        $inline_keyboard[] = [
            [
                'text' => 'search \'test\' inline',
                'switch_inline_query' => 'test'
            ],
            [
                'text' => 'search \'cats\' inline',
                'switch_inline_query' => 'cats'
            ]
        ];
        $inline_keyboard[] = [
            [
                'text' => 'search \'earth\' inline',
                'switch_inline_query' => 'earth'
            ],
        ];

        $inline_keyboards[] = $inline_keyboard;
        unset($inline_keyboard);

        //3
        $inline_keyboard[] = [
            [
                'text' => 'open url',
                'url' => 'https://github.com/akalongman/php-telegram-bot'
            ]
        ];
        $inline_keyboard[] = [
            [
                'text' => 'switch to inline',
                'switch_inline_query' => 'thumb up'
            ]
        ];
        $inline_keyboard[] = [
            [
                'text' => 'send callback query',
                'callback_data' => 'thumb up'
            ],
            [
                'text' => 'send callback query (no alert)',
                'callback_data' => 'thumb down'
            ]
        ];

        $inline_keyboards[] = $inline_keyboard;
        unset($inline_keyboard);

        $data['reply_markup'] = new InlineKeyboardMarkup(
            [
                'inline_keyboard' => $inline_keyboards[3],
            ]
        );

        return Request::sendMessage($data);
    }
}
