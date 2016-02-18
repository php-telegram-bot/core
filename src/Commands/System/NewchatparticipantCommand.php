<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * New chat participant command
 */
class NewchatparticipantCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Newchatparticipant';
    protected $description = 'New Chat Participant';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $participant = $message->getNewChatParticipant();

        if (strtolower($participant->getUsername()) === strtolower($this->getTelegram()->getBotName())) {
            $text = 'Hi there!';
        } else {
            $text = 'Hi ' . $participant->tryMention() . '!';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data)->isOk();
    }
}
