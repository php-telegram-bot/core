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
 * Super group chat created command
 */
class SupergroupchatcreatedCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Supergroupchatcreated';
    protected $description = 'Super group chat created';
    protected $version = '1.0.1';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    /*public function execute()
    {
        //$message = $this->getMessage();
        //$supergroup_chat_created = $message->getSuperGroupChatCreated();
    }*/
}
