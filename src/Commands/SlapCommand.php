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
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;

class SlapCommand extends Command
{
    /**
     * Name
     *
     * @var string
     */
    protected $name = 'slap';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Slap someone with their username';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = '/slap <@user>';

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * If this command is enabled
     *
     * @var boolean
     */
    protected $enabled = true;

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $text = $message->getText(true);
        
        $sender='@'.$message->getFrom()->getUsername();

        $data = array();
        $data['chat_id'] = $chat_id;

        //username validation
        $test=preg_match('/@[\w_]{5,}/', $text);
        if ($test===0) {
            $data['text'] = $sender.' sorry no one to slap around..';
        } else {
            $data['text'] = $sender.' slaps '.$text.' around a bit with a large trout';
        }

        $result = Request::sendMessage($data);
        return $result->isOk();
    }
}
