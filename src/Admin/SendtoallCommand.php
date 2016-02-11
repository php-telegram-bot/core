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
use Longman\TelegramBot\Request;

/**
 * Admin "/sendtoall" command
 */
class SendtoallCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'sendtoall';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Send the message to all the user\'s bot';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '/sendall <message to send>';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * If this command is public
     *
     * @var boolean
     */
    protected $public = true;

    /**
     * If this command needs mysql
     *
     * @var boolean
     */
    protected $need_mysql = true;

    /**
     * Execution if MySQL is required but not available
     *
     * @return boolean
     */
    public function executeNoDB()
    {
        //Preparing message
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Sorry no database connection, unable to execute "' . $this->name . '" command.',
        ];

        return Request::sendMessage($data)->isOk();
    }

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

        if (empty($text)) {
            $text = 'Write the message to send: /sendall <message>';
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
                print_r($result);
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
        }
        if ($tot === 0) {
            $text = 'No users or chats found..';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data)->isOk();
    }
}
