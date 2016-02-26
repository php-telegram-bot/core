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

use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ReplyKeyboardHide;

/**
 * User "/cancel" command
 */
class CancelCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'cancel';
    protected $description = 'Exit from a conversation and hide keyboard';
    protected $usage = '/cancel';
    protected $version = '0.1.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $user_id = $message->getFrom()->getId();
        $chat_id = $message->getChat()->getId();

        //Cancel current conversation if any
        (new Conversation($user_id, $chat_id))->cancel();

        $data = [
            'reply_markup' => new ReplyKeyboardHide(['selective' => true]),
            'chat_id' => $chat_id,
            'text' => 'Conversation canceled!',
        ];
        return  Request::sendMessage($data);
    }

    /**
     * {@inheritdoc}
     */
    public function executeNoDB()
    {
        //Database not setted or without connection
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'reply_markup' => new ReplyKeyboardHide(['selective' => true]),
            'chat_id' => $chat_id,
            'text' => 'Keyboard hidden!',
        ];
        return Request::sendMessage($data);
    }
}
