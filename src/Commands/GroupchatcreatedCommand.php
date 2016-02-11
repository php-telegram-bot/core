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
 * Group chat created command
 */
class GroupchatcreatedCommand extends Command
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Groupchatcreated';
    protected $description = 'Group chat created';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        //$message = $this->getMessage();
        //$group_chat_created = $message->getGroupChatCreated();

        //System command, do nothing
        return true;
    }
}
