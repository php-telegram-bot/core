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
use Longman\TelegramBot\Exception\TelegramException;

class SendtochannelCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'sendtochannel';

    /**
     * @var string
     */
    protected $description = 'Send message to a channel';

    /**
     * @var string
     */
    protected $usage = '/sendtochannel <message to send>';

    /**
     * @var string
     */
    protected $version = '0.2.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Conversation Object
     *
     * @var \Longman\TelegramBot\Conversation
     */
    protected $conversation;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse|mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();

        $type = $message->getType();
        // 'Cast' the command type into message to protect the machine state
        // if the commmad is recalled when the conversation is already started
        $type = ($type === 'command') ? 'Message' : $type;

        $text           = trim($message->getText(true));
        $text_yes_or_no = ($text === 'Yes' || $text === 'No');

        $data = [
            'chat_id' => $chat_id,
        ];

        // Conversation
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());
        $notes              = &$this->conversation->notes;

        $channels = (array)$this->getConfig('your_channel');
        if (isset($notes['state'])) {
            $state = $notes['state'];
        } else {
            $state                    = (count($channels) === 0) ? -1 : 0;
            $notes['last_message_id'] = $message->getMessageId();
        }

        switch ($state) {
            case -1:
                // getConfig has not been configured asking for channel to administer
                if ($type !== 'Message' || $text === '') {
                    $notes['state'] = -1;
                    $this->conversation->update();

                    $data['text']         = 'Insert the channel name: (@yourchannel)';
                    $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                    $result               = Request::sendMessage($data);

                    break;
                }
                $notes['channel']         = $text;
                $notes['last_message_id'] = $message->getMessageId();
                // Jump to state 1
                goto insert;

            // no break
            default:
            case 0:
                // getConfig has been configured choose channel
                if ($type !== 'Message' || !in_array($text, $channels, true)) {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $keyboard = [];
                    foreach ($channels as $channel) {
                        $keyboard[] = [$channel];
                    }
                    $data['reply_markup'] = new ReplyKeyboardMarkup(
                        [
                            'keyboard'          => $keyboard,
                            'resize_keyboard'   => true,
                            'one_time_keyboard' => true,
                            'selective'         => true,
                        ]
                    );

                    $data['text'] = 'Select a channel from the keyboard:';
                    $result       = Request::sendMessage($data);
                    break;
                }
                $notes['channel']         = $text;
                $notes['last_message_id'] = $message->getMessageId();

            // no break
            case 1:
                insert:
                if (($type === 'Message' && $text === '') || $notes['last_message_id'] === $message->getMessageId()) {
                    $notes['state'] = 1;
                    $this->conversation->update();

                    $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                    $data['text']         = 'Insert the content you want to share: text, photo, audio...';
                    $result               = Request::sendMessage($data);
                    break;
                }
                $notes['last_message_id'] = $message->getMessageId();
                $notes['message']         = $message->reflect();
                $notes['message_type']    = $type;
            // no break
            case 2:
                if (!$text_yes_or_no || $notes['last_message_id'] === $message->getMessageId()) {
                    $notes['state'] = 2;
                    $this->conversation->update();

                    // Execute this just with object that allow caption
                    if ($notes['message_type'] === 'Video' || $notes['message_type'] === 'Photo') {
                        $data['reply_markup'] = new ReplyKeyboardMarkup(
                            [
                                'keyboard'          => [['Yes', 'No']],
                                'resize_keyboard'   => true,
                                'one_time_keyboard' => true,
                                'selective'         => true,
                            ]
                        );

                        $data['text'] = 'Would you like to insert a caption?';
                        if (!$text_yes_or_no && $notes['last_message_id'] !== $message->getMessageId()) {
                            $data['text'] .= PHP_EOL . 'Type Yes or No';
                        }
                        $result = Request::sendMessage($data);
                        break;
                    }
                }
                $notes['set_caption']     = ($text === 'Yes');
                $notes['last_message_id'] = $message->getMessageId();
            // no break
            case 3:
                if ($notes['set_caption'] && ($notes['last_message_id'] === $message->getMessageId() || $type !== 'Message')) {
                    $notes['state'] = 3;
                    $this->conversation->update();

                    $data['text']         = 'Insert caption:';
                    $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);
                    $result               = Request::sendMessage($data);
                    break;
                }
                $notes['last_message_id'] = $message->getMessageId();
                $notes['caption']         = $text;
            // no break
            case 4:
                if (!$text_yes_or_no || $notes['last_message_id'] === $message->getMessageId()) {
                    $notes['state'] = 4;
                    $this->conversation->update();

                    $data['text'] = 'Message will look like this:';
                    $result       = Request::sendMessage($data);

                    if ($notes['message_type'] !== 'command') {
                        if ($notes['set_caption']) {
                            $data['caption'] = $notes['caption'];
                        }
                        $this->sendBack(new Message($notes['message'], $this->telegram->getBotName()), $data);

                        $data['reply_markup'] = new ReplyKeyboardMarkup(
                            [
                                'keyboard'          => [['Yes', 'No']],
                                'resize_keyboard'   => true,
                                'one_time_keyboard' => true,
                                'selective'         => true,
                            ]
                        );

                        $data['text'] = 'Would you like to post it?';
                        if (!$text_yes_or_no && $notes['last_message_id'] !== $message->getMessageId()) {
                            $data['text'] .= PHP_EOL . 'Type Yes or No';
                        }
                        $result = Request::sendMessage($data);
                    }
                    break;
                }

                $notes['post_message']    = ($text === 'Yes');
                $notes['last_message_id'] = $message->getMessageId();
            // no break
            case 5:
                $data['reply_markup'] = new ReplyKeyBoardHide(['selective' => true]);

                if ($notes['post_message']) {
                    $data['text'] = $this->publish(
                        new Message($notes['message'], $this->telegram->getBotName()),
                        $notes['channel'],
                        $notes['caption']
                    );
                } else {
                    $data['text'] = 'Abort by user, message not sent..';
                }

                $this->conversation->stop();
                $result = Request::sendMessage($data);
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
     * @todo This method will be moved to a higher level maybe in AdminCommand or Command
     * @todo Looking for a more significant name
     *
     * @param \Longman\TelegramBot\Entities\Message $message
     * @param array                                 $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    protected function sendBack(Message $message, array $data)
    {
        $type = $message->getType();
        $type = ($type === 'command') ? 'Message' : $type;
        if ($type === 'Message') {
            $data['text'] = $message->getText(true);
        } elseif ($type === 'Audio') {
            $data['audio']     = $message->getAudio()->getFileId();
            $data['duration']  = $message->getAudio()->getDuration();
            $data['performer'] = $message->getAudio()->getPerformer();
            $data['title']     = $message->getAudio()->getTitle();
        } elseif ($type === 'Document') {
            $data['document'] = $message->getDocument()->getFileId();
        } elseif ($type === 'Photo') {
            $data['photo'] = $message->getPhoto()[0]->getFileId();
        } elseif ($type === 'Sticker') {
            $data['sticker'] = $message->getSticker()->getFileId();
        } elseif ($type === 'Video') {
            $data['video'] = $message->getVideo()->getFileId();
        } elseif ($type === 'Voice') {
            $data['voice'] = $message->getVoice()->getFileId();
        } elseif ($type === 'Location') {
            $data['latitude']  = $message->getLocation()->getLatitude();
            $data['longitude'] = $message->getLocation()->getLongitude();
        }
        $callback_path     = 'Longman\TelegramBot\Request';
        $callback_function = 'send' . $type;
        if (!method_exists($callback_path, $callback_function)) {
            throw new TelegramException('Methods: ' . $callback_function . ' not found in class Request.');
        }

        return call_user_func_array($callback_path . '::' . $callback_function, [$data]);
    }

    /**
     * Publish a message to a channel and return success or failure message
     *
     * @param \Longman\TelegramBot\Entities\Message $message
     * @param int                                   $channel
     * @param string|null                           $caption
     *
     * @return string
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    protected function publish(Message $message, $channel, $caption = null)
    {
        $data = [
            'chat_id' => $channel,
            'caption' => $caption,
        ];

        if ($this->sendBack($message, $data)->isOk()) {
            $response = 'Message sent successfully to: ' . $channel;
        } else {
            $response = 'Message not sent to: ' . $channel . PHP_EOL .
                        '- Does the channel exist?' . PHP_EOL .
                        '- Is the bot an admin of the channel?';
        }

        return $response;
    }

    /**
     * Execute without db
     *
     * @todo Why send just to the first found channel?
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function executeNoDb()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text    = trim($message->getText(true));

        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Usage: ' . $this->getUsage(),
        ];

        if ($text !== '') {
            $channels      = (array)$this->getConfig('your_channel');
            $first_channel = $channels[0];
            $data['text']  = $this->publish(new Message($message->reflect(), $this->telegram->getBotName()),
                $first_channel);
        }

        return Request::sendMessage($data);
    }
}
