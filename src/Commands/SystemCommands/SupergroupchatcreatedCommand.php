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
use Longman\TelegramBot\Request;

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
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $text = '';

        if ($message->getSuperGroupChatCreated()) {
            $text = 'Your group has become a Supergroup!' . "\n";
            $text .= 'Chat id has changed from ' . $message->getMigrateFromChatId() . ' to ' . $message->getMigrateToChatId();
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data)->isOk();
    }
}
