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

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ForceReply;
use Longman\TelegramBot\Entities\ReplyKeyboardHide;
use Longman\TelegramBot\Entities\ReplyKeyboardMarkup;

/**
 * User "/survery" command
 */
class SurveyCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'survey';
    protected $description = 'Survery for bot users';
    protected $usage = '/survey';
    protected $version = '0.1.1';
    protected $need_mysql = true;
    /**#@-*/

    /**
     * Conversation Object
     *
     * @var Longman\TelegramBot\Conversation
     */
    protected $conversation;

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat = $message->getChat();
        $user = $message->getFrom();
        $text = $message->getText(true);

        $chat_id = $chat->getId();
        $user_id = $user->getId();

        //Preparing Respose
        $data = [];
        if ($chat->isGroupChat() || $chat->isSuperGroup()) {
            //reply to message id is applied by default
            $data['reply_to_message_id'] = $message_id;
            //Force reply is applied by default to so can work with privacy on
            $data['reply_markup'] = new ForceReply([ 'selective' => true]);
        }
        $data['chat_id'] = $chat_id;

        //Conversation start
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        //cache data from the tracking session if any
        if (!isset($this->conversation->notes['state'])) {
            $state = '0';
        } else {
            $state = $this->conversation->notes['state'];
        }

        //state machine
        //entrypoint of the machine state if given by the track
        //Every time the step is achived the track is updated
        switch ($state) {
            case 0:
                if (empty($text)) {
                    $this->conversation->notes['state'] = 0;
                    $this->conversation->update();
    
                    $data['text'] = 'Type your name:';
                    $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['name'] = $text;
                $text = '';
                // no break
            case 1:
                if (empty($text)) {
                    $this->conversation->notes['state'] = 1;
                    $this->conversation->update();
    
                    $data['text'] = 'Type your surname:';
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['surname'] = $text;
                ++$state;
                $text = '';

                // no break
            case 2:
                if (empty($text) || !is_numeric($text)) {
                    $this->conversation->notes['state'] = 2;
                    $this->conversation->update();

                    $data['text'] = 'Type your age:';
                    if (!empty($text) && !is_numeric($text)) {
                        $data['text'] = 'Type your age, must be a number';
                    }
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['age'] = $text;
                $text = '';

                // no break
            case 3:
                if (empty($text) || !($text == 'M' || $text == 'F')) {
                    $this->conversation->notes['state'] = 3;
                    $this->conversation->update();

                    $keyboard = [['M','F']];
                    $reply_keyboard_markup = new ReplyKeyboardMarkup(
                        [
                            'keyboard' => $keyboard ,
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true,
                            'selective' => true
                        ]
                    );
                    $data['reply_markup'] = $reply_keyboard_markup;
                    $data['text'] = 'Select your gender:';
                    if (!empty($text) && !($text == 'M' || $text == 'F')) {
                        $data['text'] = 'Select your gender, choose a keyboard option:';
                    }
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['gender'] = $text;
                $text = '';
           
                // no break
            case 4:
                if (is_null($message->getLocation())) {
                    $this->conversation->notes['state'] = 4;
                    $this->conversation->update();

                    $data['text'] = 'Insert your home location (need location object):';
                    $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                    $result = Request::sendMessage($data);
                    break;
                }

                $this->conversation->notes['longitude'] = $message->getLocation()->getLongitude();
                $this->conversation->notes['latitude'] = $message->getLocation()->getLatitude();

                // no break
            case 5:
                if (is_null($message->getPhoto())) {
                    $this->conversation->notes['state'] = 5;
                    $this->conversation->update();

                    $data['text'] = 'Insert your picture:';
                    $result = Request::sendMessage($data);
                    break;
                }
                $this->conversation->notes['photo_id'] = $message->getPhoto()[0]->getFileId();

                // no break
            case 6:
                $out_text = '/Survey result:' . "\n";
                unset($this->conversation->notes['state']);
                foreach ($this->conversation->notes as $k => $v) {
                    $out_text .= "\n" . ucfirst($k).': ' . $v;
                }

                $data['photo'] = $this->conversation->notes['photo_id'];
                $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                $data['caption'] = $out_text;
                $this->conversation->stop();
                $result = Request::sendPhoto($data);
                break;
        }
        return $result;
    }
}
