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
 * Admin "/sendtochannel" command
 */
class SendtochannelCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'sendtochannel';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Send message to a channel';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '/sendchannel <message to send>';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '0.1.0';

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
    protected $need_mysql = false;

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

        return Request::sendMessage($data)->isOk();
    }
}
