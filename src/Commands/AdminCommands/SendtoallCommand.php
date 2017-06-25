<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\AdminCommands;

use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Admin "/sendtoall" command
 */
class SendtoallCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'sendtoall';

    /**
     * @var string
     */
    protected $description = 'Send the message to all of the bot\'s users';

    /**
     * @var string
     */
    protected $usage = '/sendtoall <message to send>';

    /**
     * @var string
     */
    protected $version = '1.4.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Execute command
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $text    = $message->getText(true);

        if ($text === '') {
            $text = 'Write the message to send: /sendtoall <message>';
        } else {
            $results = Request::sendToActiveChats(
                'sendMessage', //callback function to execute (see Request.php methods)
                ['text' => $text], //Param to evaluate the request
                [
                    'groups'      => true,
                    'supergroups' => true,
                    'channels'    => false,
                    'users'       => true,
                ]
            );

            $total  = 0;
            $failed = 0;

            $text = 'Message sent to:' . PHP_EOL;

            /** @var ServerResponse $result */
            foreach ($results as $result) {
                $name = '';
                $type = '';
                if ($result->isOk()) {
                    $status = '✔️';

                    /** @var Message $message */
                    $message = $result->getResult();
                    $chat    = $message->getChat();
                    if ($chat->isPrivateChat()) {
                        $name = $chat->getFirstName();
                        $type = 'user';
                    } else {
                        $name = $chat->getTitle();
                        $type = 'chat';
                    }
                } else {
                    $status = '✖️';
                    ++$failed;
                }
                ++$total;

                $text .= $total . ') ' . $status . ' ' . $type . ' ' . $name . PHP_EOL;
            }
            $text .= 'Delivered: ' . ($total - $failed) . '/' . $total . PHP_EOL;

            if ($total === 0) {
                $text = 'No users or chats found..';
            }
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
