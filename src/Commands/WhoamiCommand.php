<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto <marco.bore@gmail.com>
 */

namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Request;

/**
 * User "/whoami" command
 */
class WhoamiCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'whoami';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Show your id, name and username';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '/whoami';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * If this command is public
     *
     * @var boolean
     */
    protected $public = true;

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $message = $this->getMessage();

        $user_id = $message->getFrom()->getId();
        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);

        //Send chat action
        Request::sendChatAction(['chat_id' => $chat_id, 'action' => 'typing']);

        $caption = 'Your Id: ' . $user_id . "\n";
        $caption .= 'Name: ' . $message->getFrom()->getFirstName()
             . ' ' . $message->getFrom()->getLastName() . "\n";
        $caption .= 'Username: ' . $message->getFrom()->getUsername();

        //Fetch user profile photo
        $limit = 10;
        $offset = null;
        $ServerResponse = Request::getUserProfilePhotos([
            'user_id' => $user_id ,
            'limit'   => $limit,
            'offset'  => $offset,
        ]);

        //Check if the request isOK
        if ($ServerResponse->isOk()) {
            $UserProfilePhoto = $ServerResponse->getResult();
            $totalcount = $UserProfilePhoto->getTotalCount();
        } else {
            $totalcount = 0;
        }

        $data = [
            'chat_id'             => $chat_id,
            'reply_to_message_id' => $message_id,
        ];

        if ($totalcount > 0) {
            $photos = $UserProfilePhoto->getPhotos();
            //I pick the latest photo with the hight definition
            $photo = $photos[0][2];
            $file_id = $photo->getFileId();

            $data['photo'] = $file_id;
            $data['caption'] = $caption;

            $result = Request::sendPhoto($data);

            //Download the image pictures
            //Download after send message response to speedup response
            $file_id = $photo->getFileId();
            $ServerResponse = Request::getFile(['file_id' => $file_id]);
            if ($ServerResponse->isOk()) {
                Request::downloadFile($ServerResponse->getResult());
            }

        } else {
            //No Photo just send text
            $data['text'] = $caption;
            $result = Request::sendMessage($data);
        }

        return $result->isOk();
    }
}
