<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\ForceReply;

/**
 * User "/forcereply" command
 */
class ForceReplyCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'forcereply';
    protected $description = 'Force reply with reply markup';
    protected $usage = '/forcereply';
    protected $version = '0.0.5';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['text'] = 'Write something:';
        $data['reply_markup'] = new ForceReply(['selective' => false]);

        return Request::sendMessage($data);
    }
}
