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

/**
 * Channel chat created command
 */
class ChannelchatcreatedCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name        = 'Channelchatcreated';

    /**
     * @var string
     */
    protected $description = 'Channel chat created';

    /**
     * @var string
     */
    protected $version     = '1.0.1';

    /*public function execute()
    {
        //$message = $this->getMessage();
        //$channel_chat_created = $message->getChannelChatCreated();
    }*/
}
