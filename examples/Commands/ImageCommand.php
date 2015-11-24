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

class ImageCommand extends Command
{
    protected $name = 'image';
    protected $description = 'Send Image';
    protected $usage = '/image';
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $public = true;

    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $text = $message->getText(true);

        $data = array();
        $data['chat_id'] = $chat_id;
        $data['caption'] = $text;


        //$result = Request::sendDocument($data,'structure.sql');
        //$result = Request::sendSticker($data, $this->telegram->getUploadPath().'/'.'image.jpg');

        $result = Request::sendPhoto($data, $this->telegram->getUploadPath().'/'.'image.jpg');
        return $result;
    }
}
