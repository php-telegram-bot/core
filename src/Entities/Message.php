<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Entities;

use Longman\TelegramBot\Exception\TelegramException;

class Message extends Entity
{
    protected $message_id;

    protected $from;

    protected $date;

    protected $chat;

    protected $forward_from;

    protected $forward_date;

    protected $reply_to_message;

    protected $text;

    protected $audio;

    protected $document;

    protected $photo;

    protected $sticker;

    protected $video;

    protected $contact;

    protected $location;

    protected $new_chat_participant;

    protected $left_chat_participant;

    protected $new_chat_title;

    protected $new_chat_photo;

    protected $delete_chat_photo;

    protected $group_chat_created;

    protected $command;

    protected $bot_name;

    public function __construct(array $data, $bot_name)
    {

        $this->bot_name = $bot_name;

        $this->message_id = isset($data['message_id']) ? $data['message_id'] : null;
        if (empty($this->message_id)) {
            throw new TelegramException('message_id is empty!');
        }

        $this->from = isset($data['from']) ? $data['from'] : null;
        if (empty($this->from)) {
            throw new TelegramException('from is empty!');
        }
        $this->from = new User($this->from);

        $this->date = isset($data['date']) ? $data['date'] : null;
        if (empty($this->date)) {
            throw new TelegramException('date is empty!');
        }

        $this->chat = isset($data['chat']) ? $data['chat'] : null;
        if (empty($this->chat)) {
            throw new TelegramException('chat is empty!');
        }
        $this->chat = new Chat($this->chat);

        $this->text = isset($data['text']) ? $data['text'] : null;

        $this->forward_from = isset($data['forward_from']) ? $data['forward_from'] : null;
        if (!empty($this->forward_from)) {
            $this->forward_from = new User($this->forward_from);
        }

        $this->forward_date = isset($data['forward_date']) ? $data['forward_date'] : null;

        $this->reply_to_message = isset($data['reply_to_message']) ? $data['reply_to_message'] : null;
        if (!empty($this->reply_to_message)) {
            $this->reply_to_message = new Message($this->reply_to_message);
        }
    }

    //return the entire command like /echo or /echo@bot1 if specified
    public function getFullCommand()
    {

        return strtok($this->text, ' ');
    }

    public function getCommand()
    {
        if (!empty($this->command)) {
            return $this->command;
        }

        $cmd = $this->getFullCommand();

        if (substr($cmd, 0, 1) === '/') {
            $cmd = substr($cmd, 1);

            //check if command is follow by botname
            $split_cmd = explode('@', $cmd);
            if (isset($split_cmd[1])) {
                //command is followed by name check if is addressed to me
                if (strtolower($split_cmd[1]) == strtolower($this->bot_name)) {
                    return $this->command = $split_cmd[0];
                }
            } else {
                //command is not followed by name
                return $this->command = $cmd;
            }
        }

        return false;
    }

    public function getMessageId()
    {

        return $this->message_id;
    }

    public function getDate()
    {

        return $this->date;
    }

    public function getFrom()
    {

        return $this->from;
    }

    public function getChat()
    {

        return $this->chat;
    }

    public function getForwardFrom()
    {

        return $this->forward_from;
    }

    public function getForwardDate()
    {

        return $this->forward_date;
    }

    public function getReplyToMessage()
    {

        return $this->reply_to_message;
    }

    public function getText($without_cmd = false)
    {
        $text = $this->text;
        if ($without_cmd) {
            $command = $this->getFullCommand();
            $text = substr($text, strlen($command . ' '), strlen($text));
        }

        return $text;
    }
}
