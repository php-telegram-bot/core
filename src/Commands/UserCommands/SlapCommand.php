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

/**
 * User "/slap" command
 */
class SlapCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'slap';

    /**
     * @var string
     */
    protected $description = 'Slap someone with their username';

    /**
     * @var string
     */
    protected $usage = '/slap <@user>';

    /**
     * @var string
     */
    protected $version = '1.0.1';

    /**
     * Command execute method
     *
     * @return mixed
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id    = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text       = $message->getText(true);

        $sender = '@' . $message->getFrom()->getUsername();

        //username validation
        $test = preg_match('/@[\w_]{5,}/', $text);
        if ($test === 0) {
            $text = $sender . ' sorry no one to slap around..';
        } else {
            $text = $sender . ' slaps ' . $text . ' around a bit with a large trout';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
