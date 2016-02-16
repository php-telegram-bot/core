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

/**
 * Channel chat created command
 */
class ChannelchatcreatedCommand extends Command
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Channelchatcreated';
    protected $description = 'Channel chat created';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        //$message = $this->getMessage();
        //$channel_chat_created = $message->getChannelChatCreated();

        //System command, do nothing
        return true;
    }
}
