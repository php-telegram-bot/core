<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

/**
 * New chat participant command
 */
class NewchatparticipantCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'Newchatparticipant';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'New Chat Participant';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '/';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * If this command is enabled
     *
     * @var boolean
     */
    protected $enabled = true;

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $participant = $message->getNewChatParticipant();

        if (strtolower($participant->getUsername()) == strtolower($this->getTelegram()->getBotName())) {
            $text = 'Hi there!';
        } else {
            $text = 'Hi '.$participant->tryMention().' !';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        $result = Request::sendMessage($data);
        return $result->isOk();
    }
}
