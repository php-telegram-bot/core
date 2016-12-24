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
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

/**
 * User "/inlinekeyboard" command
 */
class InlinekeyboardCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'inlinekeyboard';
    protected $description = 'Show inline keyboard';
    protected $usage = '/inlinekeyboard';
    protected $version = '0.1.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $chat_id = $this->getMessage()->getChat()->getId();
		
		$switch_element = ['true', 'false'];
		$switch_element_rand = $switch_element[mt_rand(0, count($switch_element) - 1)];

		$inline_keyboard = [];
        $inline_keyboard[] = [
            ['text' => 'inline', 'switch_inline_query' => 'true'],
            ['text' => 'callback', 'callback_data' => 'identifier'],
            ['text' => 'open url', 'url' => 'https://github.com/akalongman/php-telegram-bot'],
        ];
		$inline_keyboard[] = [
		['text' => 'click', 'switch_inline_query_current_chat' => $switch_element_rand]];

        $data = [
            'chat_id'      => $chat_id,
            'text'         => 'inline keyboard',
            'reply_markup' => new InlineKeyboard(...$inline_keyboard),
        ];

        return Request::sendMessage($data);
    }
}
