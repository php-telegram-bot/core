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

class ChatsCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'chats';

    /**
     * @var string
     */
    protected $description = 'List or search all chats stored by the bot';

    /**
     * @var string
     */
    protected $usage = '/chats, /chats * or /chats <search string>';

    /**
     * @var string
     */
    protected $version = '1.0.2';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method
     *
     * @return mixed
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $text    = trim($message->getText(true));

        $results = DB::selectChats(
            true, //Select groups (group chat)
            true, //Select supergroups (super group chat)
            true, //Select users (single chat)
            null, //'yyyy-mm-dd hh:mm:ss' date range from
            null, //'yyyy-mm-dd hh:mm:ss' date range to
            null, //Specific chat_id to select
            ($text === '' || $text == '*') ? null : $text //Text to search in user/group name
        );

        $user_chats        = 0;
        $group_chats       = 0;
        $super_group_chats = 0;

        if ($text === '') {
            $text_back = '';
        } elseif ($text == '*') {
            $text_back = 'List of all bot chats:' . "\n";
        } else {
            $text_back = 'Chat search results:' . "\n";
        }

        foreach ($results as $result) {
            //Initialize a chat object
            $result['id'] = $result['chat_id'];
            $chat         = new Chat($result);

            $whois = $chat->getId();
            if ($this->telegram->getCommandObject('whois')) {
                // We can't use '-' in command because part of it will become unclickable
                $whois = '/whois' . str_replace('-', 'g', $chat->getId());
            }

            if ($chat->isPrivateChat()) {
                if ($text != '') {
                    $text_back .= '- P ' . $chat->tryMention() . ' [' . $whois . ']' . "\n";
                }

                ++$user_chats;
            } elseif ($chat->isSuperGroup()) {
                if ($text != '') {
                    $text_back .= '- S ' . $chat->getTitle() . ' [' . $whois . ']' . "\n";
                }

                ++$super_group_chats;
            } elseif ($chat->isGroupChat()) {
                if ($text != '') {
                    $text_back .= '- G ' . $chat->getTitle() . ' [' . $whois . ']' . "\n";
                }

                ++$group_chats;
            }
        }

        if (($user_chats + $group_chats + $super_group_chats) === 0) {
            $text_back = 'No chats found..';
        } else {
            $text_back .= "\n" . 'Private Chats: ' . $user_chats;
            $text_back .= "\n" . 'Group: ' . $group_chats;
            $text_back .= "\n" . 'Super Group: ' . $super_group_chats;
            $text_back .= "\n" . 'Total: ' . ($user_chats + $group_chats + $super_group_chats);

            if ($text === '') {
                $text_back .= "\n\n" . 'List all chats: /' . $this->name . ' *' . "\n" . 'Search for chats: /' . $this->name . ' <search string>';
            }
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text_back,
        ];

        return Request::sendMessage($data);
    }
}
