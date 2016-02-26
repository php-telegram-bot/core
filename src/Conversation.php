<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot;

use Longman\TelegramBot\Command;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\ConversationDB;
use Longman\TelegramBot\Entities\Update;

/**
 * Class Conversation
 */
class Conversation
{
    /**
     * Conversation has been fetched true false
     *
     * @var bool
     */
    protected $is_fetched = false;

    /**
     * All information fetched from the database
     *
     * @var array
     */
    protected $conversation = null;

    /**
     * Data stored inside the Conversation
     *
     * @var array
     */
    protected $data = null;

    /**
     * Telegram user id
     *
     * @var int
     */
    protected $user_id;

    /**
     * Telegram chat id
     *
     * @var int
     */
    protected $chat_id;

    /**
     * Group name let you share the session among commands
     * Call this as the same name of the command if you don't need to share the conversation
     *
     * @var string
     */
    protected $group_name;

    /**
     * Command to be executed if the conversation is active
     *
     * @var string
     */
    protected $command;

    /**
     * Conversation contructor to initialize a new conversation
     *
     * @param int    $user_id
     * @param int    $chat_id
     * @param string $group_name
     * @param string $command
     */
    public function __construct($user_id, $chat_id, $group_name = null, $command = null)
    {
        if (is_null($command)) {
            $command = $group_name;
        }

        $this->user_id = $user_id;
        $this->chat_id = $chat_id;
        $this->command = $command;
        $this->group_name = $group_name;
    }

    /**
     * Check if the conversation already exists
     *
     * @return bool
     */
    protected function conversationExist()
    {
        //Conversation info already fetched
        if ($this->is_fetched) {
            return true;
        }
        //Select an active conversation
        $conversation = ConversationDB::selectConversation($this->user_id, $this->chat_id, 1);
        $this->is_fetched = true;

        if (isset($conversation[0])) {
            //Pick only the first element
            $this->conversation = $conversation[0];

            if (is_null($this->group_name)) {
                //Conversation name and command has not been specified. command has to be retrieved
                return true;
            }

            //A conversation with the same name was already opened, store the data inside the class
            if ($this->conversation['conversation_name'] == $this->group_name) {
                $this->data = json_decode($this->conversation['data'], true);
                return true;
            }

            //A conversation with a different name has been opened, unset the DB one and recreate a new one
            ConversationDB::updateConversation(['status' => 'cancelled'], ['chat_id' => $this->chat_id, 'user_id' => $this->user_id, 'status' => 'active']);
            return false;
        }

        $this->conversation = null;
        return false;
    }

    /**
     * Check if a conversation has already been created in the database. If the conversation is not found, a new conversation is created. Start fetches the data stored in the database.
     *
     * @return bool
     */
    public function start()
    {
        if (!$this->conversationExist()) {
            $status = ConversationDB::insertConversation($this->command, $this->group_name, $this->user_id, $this->chat_id);
            $this->is_fetched = true;
        }
        return true;
    }

    /**
     * Store the array/variable in the database with json_encode() function
     *
     * @todo Verify the query before assigning the $data member variable
     *
     * @param array $data
     */
    public function update($data)
    {
        //Conversation must exist!
        if ($this->conversationExist()) {
            $fields['data'] = json_encode($data);

            ConversationDB::updateConversation($fields, ['chat_id' => $this->chat_id, 'user_id' => $this->user_id, 'status' => 'active']);
            //TODO verify query success before convert the private var
            $this->data = $data;
        }
    }

    /**
     * Delete the conversation from the database
     *
     * Currently the Conversation is not deleted but just set to 'stopped'
     *
     * @todo should return something
     */
    public function stop()
    {
        if ($this->conversationExist()) {
            ConversationDB::updateConversation(['status' => 'stopped'], ['chat_id' => $this->chat_id, 'user_id' => $this->user_id, 'status' => 'active']);
        }
    }

    /**
     * Retrieve the command to execute from the conversation
     *
     * @return string|null
     */
    public function getConversationCommand()
    {
        if ($this->conversationExist()) {
            return $this->conversation['conversation_command'];
        }
        return null;
    }

    /**
     * Retrieve the data stored in the conversation
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
