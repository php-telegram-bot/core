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
use Longman\TelegramBot\Request;

/**
 * Admin "/sendtoall" command
 */
class SendtoallCommand extends AdminCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'sendtoall';
    protected $description = 'Send the message to all the user\'s bot';
    protected $usage = '/sendtoall <message to send>';
    protected $version = '1.2.1';
    protected $need_mysql = true;
    /**#@-*/

    /**
     * Execute command
     *
     * @todo Don't use empty, as a string of '0' is regarded to be empty
     *
     * @return boolean
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);

        if (empty($text)) {
            $text = 'Write the message to send: /sendtoall <message>';
        } else {
            $results = Request::sendToActiveChats(
                'sendMessage', //callback function to execute (see Request.php methods)
                ['text' => $text], //Param to evaluate the request
                true, //Send to groups (group chat)
                true, //Send to super groups chats (super group chat)
                true, //Send to users (single chat)
                null, //'yyyy-mm-dd hh:mm:ss' date range from
                null  //'yyyy-mm-dd hh:mm:ss' date range to
            );

            $tot = 0;
            $fail = 0;

            $text = 'Message sent to:' . "\n";
            foreach ($results as $result) {
                $status = '';
                $type = '';
                if ($result->isOk()) {
                    $status = '✔️';

                    $ServerResponse = $result->getResult();
                    $chat = $ServerResponse->getChat();
                    if ($chat->isPrivateChat()) {
                        $name = $chat->getFirstName();
                        $type = 'user';
                    } else {
                        $name = $chat->getTitle();
                        $type = 'chat';
                    }
                } else {
                    $status = '✖️';
                    ++$fail;
                }
                ++$tot;

                $text .= $tot . ') ' . $status . ' ' . $type . ' ' . $name . "\n";
            }
            $text .= 'Delivered: ' . ($tot - $fail) . '/' . $tot . "\n";

            if ($tot === 0) {
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
