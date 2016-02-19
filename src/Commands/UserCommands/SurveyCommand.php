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
use Longman\TelegramBot\Tracking;
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
    protected $version = '0.1.0';
    protected $need_mysql = true;
    /**#@-*/

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        $update = $this->getUpdate();
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

        //tracking
        $path = new Tracking($user_id, $chat_id, $this->getName());
        $path->startTrack();

        //cache data from the tracking session if any
        $session = $path->GetData();
        if (!isset($session['state'])) {
            $state = '0';
        } else {
            $state = $session['state'];
        }

        //state machine
        //entrypoint of the machine state if given by the track
        //Every time the step is achived the track is updated
        switch ($state) {
            case 0:
                if (empty($text)) {
                    $session['state'] = '0';
                    $path->updateTrack($session);
    
                    $data['text'] = 'Type your name:';
                    $result = Request::sendMessage($data);
                    break;
                }
                $session['name'] = $text;
                $text = '';
                // no break
            case 1:
                if (empty($text)) {
                    $session['state'] = 1;
                    $path->updateTrack($session);
    
                    $data['text'] = 'Type your surname:';
                    $result = Request::sendMessage($data);
                    break;
                }
                $session['surname'] = $text;
                ++$state;
                $text = '';

                // no break
            case 2:
                if (empty($text) || !is_numeric($text)) {
                    $session['state'] = '2';
                    $path->updateTrack($session);
                    $data['text'] = 'Type your age:';
                    if (!empty($text) && !is_numeric($text)) {
                        $data['text'] = 'Type your age, must be a number';
                    }
                    $result = Request::sendMessage($data);
                    break;
                }
                $session['age'] = $text;
                $text = '';

                // no break
            case 3:
                if (empty($text) || !($text == 'M' || $text == 'F')) {
                    $session['state'] = '3';
                    $path->updateTrack($session);

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
                $session['gender'] = $text;
                $text = '';
           
                // no break
            case 4:
                if (is_null($message->getLocation())) {
                    $session['state'] = '4';
                    $path->updateTrack($session);

                    $data['text'] = 'Insert your home location (need location object):';
                    $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                    $result = Request::sendMessage($data);
                    break;
                }

                $session['longitude'] = $message->getLocation()->getLongitude();
                $session['latitude'] = $message->getLocation()->getLatitude();

                // no break
            case 5:
                if (is_null($message->getPhoto())) {
                    $session['state'] = '5';
                    $path->updateTrack($session);

                    $data['text'] = 'Insert your picture:';
                    $result = Request::sendMessage($data);
                    break;
                }
                $session['photo_id'] = $message->getPhoto()[0]->getFileId();

                // no break
            case 6:
                $path->stopTrack();
                $out_text = '/Survey result:' . "\n";
                unset($session['state']);
                foreach ($session as $k => $v) {
                    $out_text .= "\n" . ucfirst($k).': ' . $v;
                }
    
                $data['photo'] = $session['photo_id'];
                $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                $data['caption'] = $out_text;
                $result = Request::sendPhoto($data);
                break;
        }
        return $result->isOk();
    }
}
