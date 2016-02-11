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
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

/**
 * Admin "/sendtochannel" command
 */
class SendtochannelCommand extends Command
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'sendtochannel';
    protected $description = 'Send message to a channel';
    protected $usage = '/sendchannel <message to send>';
    protected $version = '0.1.0';
    protected $enabled = true;
    protected $public = true;
    protected $need_mysql = false;
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
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);

        if (empty($text)) {
            $text_back = 'Write the message to send: /sendtochannel <message>';
        } else {
            $your_channel = $this->getConfig('your_channel');
            //Send message to channel
            $data = [
                'chat_id' => $your_channel,
                'text'    => $text,
            ];

            $result = Request::sendMessage($data);
            if ($result->isOk()) {
                $text_back = 'Message sent succesfully to: ' . $your_channel;
            } else {
                $text_back = 'Sorry message not sent to: ' . $your_channel;
            }
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text_back,
        ];

        $result = Request::sendMessage($data);
        return $result->isOk();
    }
}
