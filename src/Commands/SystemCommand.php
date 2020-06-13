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

use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

abstract class SystemCommand extends Command
{
    /**
     * @var bool Try to execute any deprecated system command.
     */
    public static $execute_deprecated = false;

    /**
     * @{inheritdoc}
     *
     * Set to empty string to disallow users calling system commands.
     */
    protected $usage = '';

    /**
     * A system command just executes
     *
     * Although system commands should just work and return a successful ServerResponse,
     * each system command can override this method to add custom functionality.
     *
     * @return ServerResponse
     */
    public function execute()
    {
        // System command, return empty ServerResponse by default
        return Request::emptyResponse();
    }

    /**
     * Method to execute any active conversation.
     *
     * @return ServerResponse|null
     * @throws TelegramException
     * @internal
     */
    protected function executeActiveConversation()
    {
        $message = $this->getMessage();
        if ($message === null) {
            return null;
        }

        $user = $message->getFrom();
        $chat = $message->getChat();
        if ($user === null || $chat === null) {
            return null;
        }

        // If a conversation is busy, execute the conversation command after handling the message.
        $conversation = new Conversation($user->getId(), $chat->getId());

        // Fetch conversation command if it exists and execute it.
        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->getTelegram()->executeCommand($command);
        }

        return null;
    }

    /**
     * BC helper method to execute deprecated system commands.
     *
     * @return ServerResponse|null
     * @throws TelegramException
     * @internal
     */
    protected function executeDeprecatedSystemCommand()
    {
        $message = $this->getMessage();
        if ($message === null) {
            return null;
        }

        // List of service messages previously handled internally.
        $service_message_getters = [
            'newchatmembers'        => 'getNewChatMembers',
            'leftchatmember'        => 'getLeftChatMember',
            'newchattitle'          => 'getNewChatTitle',
            'newchatphoto'          => 'getNewChatPhoto',
            'deletechatphoto'       => 'getDeleteChatPhoto',
            'groupchatcreated'      => 'getGroupChatCreated',
            'supergroupchatcreated' => 'getSupergroupChatCreated',
            'channelchatcreated'    => 'getChannelChatCreated',
            'migratefromchatid'     => 'getMigrateFromChatId',
            'migratetochatid'       => 'getMigrateToChatId',
            'pinnedmessage'         => 'getPinnedMessage',
            'successfulpayment'     => 'getSuccessfulPayment',
        ];

        foreach ($service_message_getters as $command => $service_message_getter) {
            // Let's check if this message is a service message.
            if ($message->$service_message_getter() === null) {
                continue;
            }

            // Make sure the command exists otherwise GenericCommand would be executed.
            if ($this->getTelegram()->getCommandObject($command) === null) {
                break;
            }

            return $this->getTelegram()->executeCommand($command);
        }

        return null;
    }
}
