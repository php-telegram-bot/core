<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Jack'lul <jacklul@jacklul.com>
 */

namespace Longman\TelegramBot\Commands\AdminCommands;

use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Request;

/**
 * Admin "/whois" command
 */
class WhoisCommand extends AdminCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'whois';
    protected $description = 'Lookup user or group info';
    protected $usage = '/whois <id> or /whois <search string>';
    protected $version = '1.1.0';
    protected $need_mysql = true;
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $command = $message->getCommand();
        $text = trim($message->getText(true));

        $data = [ 'chat_id' => $chat_id ];

        //No point in replying to messages in private chats
        if (!$message->getChat()->isPrivateChat()) {
            $data['reply_to_message_id'] = $message->getMessageId();
        }

        if ($command !== 'whois') {
            $text = substr($command, 5);

            //We need that '-' now, bring it back
            if ((substr($text, 0, 1) == 'g')) {
                $text = str_replace('g', '-', $text);
            }
        }

        if ($text === '') {
            $text = 'Provide the id to lookup: /whois <id>';
        } else {
            $user_id = $text;

            if (is_numeric($text)) {
                $result = DB::selectChats(
                    true, //Select groups (group chat)
                    true, //Select supergroups (super group chat)
                    true, //Select users (single chat)
                    null, //'yyyy-mm-dd hh:mm:ss' date range from
                    null, //'yyyy-mm-dd hh:mm:ss' date range to
                    $user_id //Specific chat_id to select
                );

                $result = $result[0];
            } else {
                $results = DB::selectChats(
                    true, //Select groups (group chat)
                    true, //Select supergroups (super group chat)
                    true, //Select users (single chat)
                    null, //'yyyy-mm-dd hh:mm:ss' date range from
                    null, //'yyyy-mm-dd hh:mm:ss' date range to
                    null, //Specific chat_id to select
                    $text //Text to search in user/group name
                );

                if (is_array($results) && count($results) == 1) {
                    $result = $results[0];
                }
            }

            if (is_array($result)) {
                $result['id'] = $result['chat_id'];
                $chat = new Chat($result);

                $user_id = $result['id'];
                $created_at = $result['chat_created_at'];
                $updated_at = $result['chat_updated_at'];
                $old_id = $result['old_id'];
            }

            if ($chat != null) {
                if ($chat->isPrivateChat()) {
                    $text = 'User ID: ' . $user_id . "\n";
                    $text .= 'Name: ' . $chat->getFirstName() . ' ' . $chat->getLastName() . "\n";

                    if ($chat->getUsername() != '') {
                        $text .= 'Username: @' . $chat->getUsername() . "\n";
                    }

                    $text .= 'First time seen: ' . $created_at . "\n";
                    $text .= 'Last activity: ' . $updated_at . "\n";

                    //Code from Whoami command
                    $limit = 10;
                    $offset = null;
                    $ServerResponse = Request::getUserProfilePhotos([
                        'user_id' => $user_id ,
                        'limit'   => $limit,
                        'offset'  => $offset,
                    ]);

                    if ($ServerResponse->isOk()) {
                        $UserProfilePhoto = $ServerResponse->getResult();
                        $totalcount = $UserProfilePhoto->getTotalCount();
                    } else {
                        $totalcount = 0;
                    }

                    if ($totalcount > 0) {
                        $photos = $UserProfilePhoto->getPhotos();
                        $photo = $photos[0][2];
                        $file_id = $photo->getFileId();

                        $data['photo'] = $file_id;
                        $data['caption'] = $text;

                        return Request::sendPhoto($data);
                    }
                } elseif ($chat->isGroupChat()) {
                    $text = 'Chat ID: ' . $user_id . (!empty($old_id) ? ' (previously: '.$old_id.')' : ''). "\n";
                    $text .= 'Type: ' . ucfirst($chat->getType()) . "\n";
                    $text .= 'Title: ' . $chat->getTitle() . "\n";
                    $text .= 'First time added to group: ' . $created_at . "\n";
                    $text .= 'Last activity: ' . $updated_at . "\n";
                }
            } elseif (is_array($results) && count($results) > 1) {
                $text = 'Multiple chats matched!';
            } else {
                $text = 'Chat not found!';
            }
        }

        $data['text'] = $text;
        return Request::sendMessage($data);
    }
}
