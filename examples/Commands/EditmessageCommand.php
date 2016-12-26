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

/**
 * User "/editmessage" command
 */
class EditmessageCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'editmessage';
    protected $description = 'Edit message';
    protected $usage = '/editmessage';
    protected $version = '1.0.0';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $reply_to_message = $message->getReplyToMessage();
		$text = $message->getText(true);

        if ($reply_to_message) {
            $message_to_edit = $reply_to_message->getMessageId();
        }

        if (isset($message_to_edit) && $message_to_edit) {
            $data_edit = [];
            $data_edit['chat_id'] = $chat_id;
            $data_edit['message_id'] = $message_to_edit;

			if (!empty($text)) {
                $data_edit['text'] = $text;
			} else {
                $data_edit['text'] = "Edited message";
			}

            return Request::editMessageText($data_edit);
        } else {
            $data = [];
            $data['chat_id'] = $chat_id;
            $data['text'] = 'Reply to any bot\'s message and use /' . $this->name . ' <your text> to edit it.';
            return Request::sendMessage($data);
        }
    }
}
