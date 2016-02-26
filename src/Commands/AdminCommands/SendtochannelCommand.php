<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\AdminCommands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ReplyKeyboardHide;
use Longman\TelegramBot\Entities\ReplyKeyboardMarkup;

class SendtochannelCommand extends AdminCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'sendtochannel';
    protected $description = 'Send message to a channel';
    protected $usage = '/sendtochannel <message to send>';
    protected $version = '0.1.2';
    protected $need_mysql = true;
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $message = $this->getMessage();
        $type = $message->getType();
        $text = trim($message->getText(true));
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        $text = $message->getText(true);

        $data = [];
        $data['chat_id'] = $chat_id;

        //tracking
        $conversation = new Conversation($user_id, $chat_id, $this->getName());
        $conversation->start();
        $session = $conversation->getData();

        if (!isset($session['state'])) {
            $state = '0';
            $session['last_message_id'] = $message->getMessageId();
        } else {
            $state = $session['state'];
        }

        $channels = (array) $this->getConfig('your_channel');

        switch ($state) {
            default:
            case 0:
                if ($type != 'Message' || !in_array(trim($text), $channels)) {
                    $session['state'] = '0';
                    $conversation->update($session);
    
                    $keyboard = [];
                    foreach ($channels as $channel) {
                        $keyboard[] = [$channel];
                    }
                    $reply_keyboard_markup = new ReplyKeyboardMarkup(
                        [
                            'keyboard' => $keyboard ,
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true,
                            'selective' => true
                        ]
                    );
                    $data['reply_markup'] = $reply_keyboard_markup;
                    $data['text'] = 'Select a channel';
                    if ($type != 'Message' || !in_array(trim($text), $channels)) {
                        $data['text'] = 'Select a channel from the keyboard:';
                    }
                    $result = Request::sendMessage($data);
                    break;
                }
                $session['channel'] = $text;
                $session['last_message_id'] = $message->getMessageId();

                // no break
            case 1:
                if ($session['last_message_id'] == $message->getMessageId()) {
                    $session['state'] = 1;
                    $conversation->update($session);
    
                    $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                    $data['text'] = 'Insert the content you want to share: text, photo, audio...';
                    $result = Request::sendMessage($data);
                    break;
                }
                $session['message'] = $message->reflect();
                $session['message_type'] = $type;
                $session['last_message_id'] = $message->getMessageId();

                // no break
            case 2:
                if ($session['last_message_id'] == $message->getMessageId() || !($text == 'Yes' || $text == 'No')) {
                    $session['state'] = 2;
                    $conversation->update($session);

                    if ($session['message_type'] == 'Video' || $session['message_type'] == 'Photo') {
                        $keyboard = [['Yes', 'No']];
                        $reply_keyboard_markup = new ReplyKeyboardMarkup(
                            [
                                'keyboard' => $keyboard ,
                                'resize_keyboard' => true,
                                'one_time_keyboard' => true,
                                'selective' => true
                            ]
                        );
                        $data['reply_markup'] = $reply_keyboard_markup;

                        $data['text'] = 'Would you insert caption?';
                        if ($session['last_message_id'] != $message->getMessageId() && !($text == 'Yes' || $text == 'No')) {
                            $data['text'] = 'Would you insert a caption?' . "\n" . 'Type Yes or No';
                        }
                        $result = Request::sendMessage($data);
                        break;
                    }
                }
                $session['set_caption'] = false;
                if ($text == 'Yes') {
                    $session['set_caption'] = true;
                }
                $session['last_message_id'] = $message->getMessageId();
                // no break
            case 3:
                if (($session['last_message_id'] == $message->getMessageId() || $type != 'Message' ) && $session['set_caption']) {
                    $session['state'] = 3;
                    $conversation->update($session);

                    $data['text'] = 'Insert caption:';
                    $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                    $result = Request::sendMessage($data);
                    break;
                }
                $session['caption'] = $text;
                $session['last_message_id'] = $message->getMessageId();
                // no break
            case 4:
                if ($session['last_message_id'] == $message->getMessageId() || !($text == 'Yes' || $text == 'No')) {
                    $session['state'] = '4';
                    $conversation->update($session);

                    $data['text'] = 'Message will look like this:';
                    $result = Request::sendMessage($data);

                    if ($session['message_type'] != 'command') {
                        if ($session['set_caption']) {
                            $data['caption'] = $session['caption'];
                        }
                        $result = $this->sendBack(new Message($session['message'], 'thisbot'), $data);

                        $data['text'] = 'Would you post it?';
                        if ($session['last_message_id'] != $message->getMessageId() && !($text == 'Yes' || $text == 'No')) {
                            $data['text'] = 'Would you post it?' . "\n" . 'Press Yes or No';
                        }
                        $keyboard = [['Yes', 'No']];
                        $reply_keyboard_markup = new ReplyKeyboardMarkup(
                            [
                                'keyboard' => $keyboard ,
                                'resize_keyboard' => true,
                                'one_time_keyboard' => true,
                                'selective' => true
                            ]
                        );
                        $data['reply_markup'] = $reply_keyboard_markup;

                        $result = Request::sendMessage($data);
                    }
                    break;
                }

                $session['post_message'] = false;
                if ($text == 'Yes') {
                    $session['post_message'] = true;
                }
                $session['last_message_id'] = $message->getMessageId();
                // no break
            case 5:
                $conversation->stop();

                $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);

                if ($session['post_message']) {
                    $data1 = [];
                    $data1['chat_id'] = $session['channel'];
                    if ($session['set_caption']) {
                        $data1['caption'] = $session['caption'];
                    }
                    $result = $this->sendBack(new Message($session['message'], 'thisbot'), $data1);

                    $text_back = 'Sorry message not sent to: '.$session['channel'];
                    if ($result->isOk()) {
                        $text_back = 'Message sent succesfully to: '.$session['channel'];
                    }
                    $data['text'] = $text_back;
                    $result = Request::sendMessage($data);
                    break;
                }
    
                $data['text'] = 'Abort by user, message not sent..';
                $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                $result = Request::sendMessage($data);
                break;
        }

        return $result;
    }

    /**
     * SendBack
     *
     * Received a message, the bot can send a copy of it to another chat/channel.
     * You don't have to care about the type of the message, the function detect it and use the proper
     * REQUEST:: function to send it.
     * $data include all the var that you need to send the message to the proper chat
     *
     * @todo This method will be moved at an higher level maybe in AdminCommand or Command
     * @todo Looking for a more significative name
     *
     * @param Longman\TelegramBot\Entities\Message $message
     * @param array $data
     *
     * @return Longman\TelegramBot\Entities\ServerResponse
     */
    public function sendBack(Message $message, array $data)
    {
        $type = $message->getType();
        if ($type == 'Message') {
            $data['text'] = $message->getText();
        } elseif ($type == 'Audio') {
            $data['audio'] = $message->getAudio()->getFileId();
            $data['duration'] = $message->getAudio()->getDuration();
            $data['performer'] = $message->getAudio()->getPerformer();
            $data['title'] = $message->getAudio()->getTitle();
        } elseif ($type == 'Document') {
            $data['document'] = $message->getDocument()->getFileId();
        } elseif ($type == 'Photo') {
            $data['photo'] = $message->getPhoto()[0]->getFileId();
        } elseif ($type == 'Sticker') {
            $data['sticker'] = $message->getSticker()->getFileId();
        } elseif ($type == 'Video') {
            $data['video'] = $message->getVideo()->getFileId();
        } elseif ($type == 'Voice') {
            $data['voice'] = $message->getVoice()->getFileId();
        } elseif ($type == 'Location') {
            $data['latitude'] = $message->getLocation()->getLatitude();
            $data['longitude'] = $message->getLocation()->getLongitude();
        }
        $callback_path = 'Longman\TelegramBot' .'\Request';
        $callback_function = 'send'.$type;
        if (! method_exists($callback_path, $callback_function)) {
            throw new TelegramException('Methods: '.$callback_function.' not found in class Request.');
        }

        $result = call_user_func_array($callback_path.'::'.$callback_function, array($data));
        return $result;
    }
}
