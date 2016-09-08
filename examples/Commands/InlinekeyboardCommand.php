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
use Longman\TelegramBot\Entities\InlineKeyboardButton;

/**
 * User "/inlinekeyboard" command
 */
class InlinekeyboardCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Inlinekeyboard';
    protected $description = 'Show inline keyboard';
    protected $usage = '/inlinekeyboard';
    protected $version = '0.0.2';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();

        $inline_keyboard = [
            new InlineKeyboardButton(['text' => 'inline', 'switch_inline_query' => 'true']),
            new InlineKeyboardButton(['text' => 'callback', 'callback_data' => 'identifier']),
            new InlineKeyboardButton(['text' => 'open url', 'url' => 'https://github.com/akalongman/php-telegram-bot']),
        ];
        $data            = [
            'chat_id'      => $message->getChat()->getId(),
            'text'         => 'inline keyboard',
            'reply_markup' => new InlineKeyboardMarkup(['inline_keyboard' => [$inline_keyboard]]),
        ];

        return Request::sendMessage($data);
    }
}
