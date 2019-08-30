<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

/**
 * Generic message command
 */
class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Execution if MySQL is required but not available
     *
     * @return ServerResponse
     */
    public function executeNoDb()
    {
        //Do nothing
        return Request::emptyResponse();
    }

    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute()
    {
        //If a conversation is busy, execute the conversation command after handling the message
        $conversation = new Conversation(
            $this->getMessage()->getFrom()->getId(),
            $this->getMessage()->getChat()->getId()
        );

        //Fetch conversation command if it exists and execute it
        if ($conversation->exists() && ($command = $conversation->getCommand())) {
            return $this->getTelegram()->executeCommand($command);
        }

        // Try to execute any deprecated system commands.
        if ($deprecated_system_command_response = $this->executeDeprecatedSystemCommand()) {
            return $deprecated_system_command_response;
        }

        return Request::emptyResponse();
    }


    public function executeDeprecatedSystemCommand()
    {
        $telegram = $this->getTelegram();
        $update   = $this->getUpdate();
        $message  = $this->getMessage();

        // List of service messages previously handled internally.
        $service_messages = [
            'editedmessage'         => [$update, 'getEditedMessage'],
            'channelpost'           => [$update, 'getChannelPost'],
            'editedchannelpost'     => [$update, 'getEditedChannelPost'],
            'newchatmembers'        => [$message, 'getNewChatMembers'],
            'leftchatmember'        => [$message, 'getLeftChatMember'],
            'newchattitle'          => [$message, 'getNewChatTitle'],
            'newchatphoto'          => [$message, 'getNewChatPhoto'],
            'deletechatphoto'       => [$message, 'getDeleteChatPhoto'],
            'groupchatcreated'      => [$message, 'getGroupChatCreated'],
            'supergroupchatcreated' => [$message, 'getSupergroupChatCreated'],
            'channelchatcreated'    => [$message, 'getChannelChatCreated'],
            'migratefromchatid'     => [$message, 'getMigrateFromChatId'],
            'migratetochatid'       => [$message, 'getMigrateToChatId'],
            'pinnedmessage'         => [$message, 'getPinnedMessage'],
            'successfulpayment'     => [$message, 'getSuccessfulPayment'],
        ];

        foreach ($service_messages as $command => $service_message_getter) {
            // Let's check if this message is a service message.
            if ($service_message_getter() === null) {
                continue;
            }

            // Make sure the command exists, otherwise GenericCommand would be executed instead!
            if ($telegram->getCommandObject($command) === null) {
                break;
            }

            return $telegram->executeCommand($command);
        }

        return null;
    }
}
