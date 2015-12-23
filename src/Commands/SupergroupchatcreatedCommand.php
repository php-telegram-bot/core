<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class SupergroupchatcreatedCommand extends Command
{
    protected $name = 'Supergroupchatcreated';
    protected $description = 'Super group chat created';
    protected $usage = '/';
    protected $version = '1.0.0';
    protected $enabled = true;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();
        $text = '';

        if ($message->getSuperGroupChatCreated()) {
            $text = "Your group has become a Supergroup!\n";
            $text .= 'Chat id has changed from'.$message->getMigrateFromChatId().' to '.$message->getMigrateToChatId();
        }

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['text'] = $text;

        $result = Request::sendMessage($data);
        return $result;
    }
}
