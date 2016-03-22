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
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Request;

/**
 * Admin "/chats" command
 */
class ChatsCommand extends AdminCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'chats';
    protected $description = 'List all chats stored by the bot';
    protected $usage = '/chats';
    protected $version = '1.0.1';
    protected $need_mysql = false;
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();

        $results = DB::selectChats(
            true, //Send to groups (group chat)
            true, //Send to supergroups (single chat)
            true, //Send to users (single chat)
            null, //'yyyy-mm-dd hh:mm:ss' date range from
            null  //'yyyy-mm-dd hh:mm:ss' date range to
        );

        $user_chats = 0;
        $group_chats = 0;
        $super_group_chats = 0;
        $text = 'List of bot chats:' . "\n";

        foreach ($results as $result) {
            //Initialize a chat object
            $result['id'] =  $result['chat_id'];
            $chat = new Chat($result);

            if ($chat->isPrivateChat()) {
                $text .= '- P ' . $chat->tryMention() . "\n";
                ++$user_chats;
            } elseif ($chat->isGroupChat()) {
                $text .= '- G ' . $chat->getTitle() . "\n";
                ++$group_chats;
            } elseif ($chat->isSuperGroup()) {
                $text .= '- S ' . $chat->getTitle() . "\n";
                ++$super_group_chats;
            }
        }

        if (($user_chats + $group_chats + $super_group_chats) === 0) {
            $text = 'No chats found..';
        } else {
            $text .= "\n" . 'Private Chats: ' . $user_chats;
            $text .= "\n" . 'Group: ' . $group_chats;
            $text .= "\n" . 'Super Group: ' . $super_group_chats;
            $text .= "\n" . 'Total: ' . ($user_chats + $group_chats + $super_group_chats);
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
