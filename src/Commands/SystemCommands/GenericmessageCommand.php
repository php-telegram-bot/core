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

use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\SystemCommand;

/**
 * Generic message command
 */
class GenericmessageCommand extends SystemCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'Genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.1';
    protected $need_mysql = true;
    /**#@-*/

    /**
     * Execution if MySQL is required but not available
     *
     * @return boolean
     */
    public function executeNoDB()
    {
        //Do nothing
        return Request::emptyResponse();
    }

    /**
     * Execute command
     *
     * @return boolean
     */
    public function execute()
    {
        //System command, fetch command to execute if conversation exist
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();
        //Fetch Conversation if exist
        $command = (new Conversation($user_id, $chat_id))->getConversationCommand();
        if (! is_null($command)) {
            return $this->telegram->executeCommand($command, $this->update);
        }
        return Request::emptyResponse();
    }
}
