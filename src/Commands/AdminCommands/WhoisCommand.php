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
use Longman\TelegramBot\Entities\PhotoSize;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\UserProfilePhotos;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

/**
 * Admin "/whois" command
 */
class WhoisCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'whois';

    /**
     * @var string
     */
    protected $description = 'Lookup user or group info';

    /**
     * @var string
     */
    protected $usage = '/whois <id> or /whois <search string>';

    /**
     * @var string
     */
    protected $version = '1.3.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $command = $message->getCommand();
        $text    = trim($message->getText(true));

        $data = ['chat_id' => $chat_id];

        //No point in replying to messages in private chats
        if (!$message->getChat()->isPrivateChat()) {
            $data['reply_to_message_id'] = $message->getMessageId();
        }

        if ($command !== 'whois') {
            $text = substr($command, 5);

            //We need that '-' now, bring it back
            if (strpos($text, 'g') === 0) {
                $text = str_replace('g', '-', $text);
            }
        }

        if ($text === '') {
            $text = 'Provide the id to lookup: /whois <id>';
        } else {
            $user_id    = $text;
            $chat       = null;
            $created_at = null;
            $updated_at = null;
            $result     = null;

            if (is_numeric($text)) {
                $results = DB::selectChats([
                    'groups'      => true,
                    'supergroups' => true,
                    'channels'    => true,
                    'users'       => true,
                    'chat_id'     => $user_id, //Specific chat_id to select
                ]);

                if (!empty($results)) {
                    $result = reset($results);
                }
            } else {
                $results = DB::selectChats([
                    'groups'      => true,
                    'supergroups' => true,
                    'channels'    => true,
                    'users'       => true,
                    'text'        => $text //Text to search in user/group name
                ]);

                if (is_array($results) && count($results) === 1) {
                    $result = reset($results);
                }
            }

            if (is_array($result)) {
                $result['id']       = $result['chat_id'];
                $result['username'] = $result['chat_username'];
                $chat               = new Chat($result);

                $user_id    = $result['id'];
                $created_at = $result['chat_created_at'];
                $updated_at = $result['chat_updated_at'];
                $old_id     = $result['old_id'];
            }

            if ($chat !== null) {
                if ($chat->isPrivateChat()) {
                    $text = 'User ID: ' . $user_id . PHP_EOL;
                    $text .= 'Name: ' . $chat->getFirstName() . ' ' . $chat->getLastName() . PHP_EOL;

                    $username = $chat->getUsername();
                    if ($username !== null && $username !== '') {
                        $text .= 'Username: @' . $username . PHP_EOL;
                    }

                    $text .= 'First time seen: ' . $created_at . PHP_EOL;
                    $text .= 'Last activity: ' . $updated_at . PHP_EOL;

                    //Code from Whoami command
                    $limit    = 10;
                    $offset   = null;
                    $response = Request::getUserProfilePhotos(
                        [
                            'user_id' => $user_id,
                            'limit'   => $limit,
                            'offset'  => $offset,
                        ]
                    );

                    if ($response->isOk()) {
                        /** @var UserProfilePhotos $user_profile_photos */
                        $user_profile_photos = $response->getResult();

                        if ($user_profile_photos->getTotalCount() > 0) {
                            $photos = $user_profile_photos->getPhotos();

                            /** @var PhotoSize $photo */
                            $photo   = $photos[0][2];
                            $file_id = $photo->getFileId();

                            $data['photo']   = $file_id;
                            $data['caption'] = $text;

                            return Request::sendPhoto($data);
                        }
                    }
                } elseif ($chat->isGroupChat()) {
                    $text = 'Chat ID: ' . $user_id . (!empty($old_id) ? ' (previously: ' . $old_id . ')' : '') . PHP_EOL;
                    $text .= 'Type: ' . ucfirst($chat->getType()) . PHP_EOL;
                    $text .= 'Title: ' . $chat->getTitle() . PHP_EOL;
                    $text .= 'First time added to group: ' . $created_at . PHP_EOL;
                    $text .= 'Last activity: ' . $updated_at . PHP_EOL;
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
