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

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ChosenInlineResult;
use Longman\TelegramBot\Entities\InlineQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Payments\PreCheckoutQuery;
use Longman\TelegramBot\Entities\Payments\ShippingQuery;
use Longman\TelegramBot\Entities\Poll;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

/**
 * Class Command
 *
 * Base class for commands. It includes some helper methods that can fetch data directly from the Update object.
 *
 * @method Message             getMessage()            Optional. New incoming message of any kind — text, photo, sticker, etc.
 * @method Message             getEditedMessage()      Optional. New version of a message that is known to the bot and was edited
 * @method Message             getChannelPost()        Optional. New post in the channel, can be any kind — text, photo, sticker, etc.
 * @method Message             getEditedChannelPost()  Optional. New version of a post in the channel that is known to the bot and was edited
 * @method InlineQuery         getInlineQuery()        Optional. New incoming inline query
 * @method ChosenInlineResult  getChosenInlineResult() Optional. The result of an inline query that was chosen by a user and sent to their chat partner.
 * @method CallbackQuery       getCallbackQuery()      Optional. New incoming callback query
 * @method ShippingQuery       getShippingQuery()      Optional. New incoming shipping query. Only for invoices with flexible price
 * @method PreCheckoutQuery    getPreCheckoutQuery()   Optional. New incoming pre-checkout query. Contains full information about checkout
 * @method Poll                getPoll()               Optional. New poll state. Bots receive only updates about polls, which are sent or stopped by the bot
 */
abstract class Command
{
    /**
     * Telegram object
     *
     * @var Telegram
     */
    protected $telegram;

    /**
     * Update object
     *
     * @var Update
     */
    protected $update;

    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Usage
     *
     * @var string
     */
    protected $usage = 'Command usage';

    /**
     * Show in Help
     *
     * @var bool
     */
    protected $show_in_help = true;

    /**
     * Version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * If this command is enabled
     *
     * @var boolean
     */
    protected $enabled = true;

    /**
     * If this command needs mysql
     *
     * @var boolean
     */
    protected $need_mysql = false;

    /*
    * Make sure this command only executes on a private chat.
    *
    * @var bool
    */
    protected $private_only = false;

    /**
     * Command config
     *
     * @var array
     */
    protected $config = [];

    /**
     * Constructor
     *
     * @param Telegram $telegram
     * @param Update   $update
     */
    public function __construct(Telegram $telegram, Update $update = null)
    {
        $this->telegram = $telegram;
        $this->setUpdate($update);
        $this->config = $telegram->getCommandConfig($this->name);
    }

    /**
     * Set update object
     *
     * @param Update $update
     *
     * @return Command
     */
    public function setUpdate(Update $update = null)
    {
        if ($update !== null) {
            $this->update = $update;
        }

        return $this;
    }

    /**
     * Pre-execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function preExecute()
    {
        if ($this->need_mysql && !($this->telegram->isDbEnabled() && DB::isDbConnected())) {
            return $this->executeNoDb();
        }

        if ($this->isPrivateOnly() && $this->removeNonPrivateMessage()) {
            $message = $this->getMessage();

            if ($user = $message->getFrom()) {
                return Request::sendMessage([
                    'chat_id'    => $user->getId(),
                    'parse_mode' => 'Markdown',
                    'text'       => sprintf(
                        "/%s command is only available in a private chat.\n(`%s`)",
                        $this->getName(),
                        $message->getText()
                    ),
                ]);
            }

            return Request::emptyResponse();
        }

        return $this->execute();
    }

    /**
     * Execute command
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    abstract public function execute();

    /**
     * Execution if MySQL is required but not available
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function executeNoDb()
    {
        return $this->replyToChat('Sorry no database connection, unable to execute "' . $this->name . '" command.');
    }

    /**
     * Get update object
     *
     * @return Update
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * Relay any non-existing function calls to Update object.
     *
     * This is purely a helper method to make requests from within execute() method easier.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Command
     */
    public function __call($name, array $arguments)
    {
        if ($this->update === null) {
            return null;
        }
        return call_user_func_array([$this->update, $name], $arguments);
    }

    /**
     * Get command config
     *
     * Look for config $name if found return it, if not return null.
     * If $name is not set return all set config.
     *
     * @param string|null $name
     *
     * @return array|mixed|null
     */
    public function getConfig($name = null)
    {
        if ($name === null) {
            return $this->config;
        }
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }

        return null;
    }

    /**
     * Get telegram object
     *
     * @return Telegram
     */
    public function getTelegram()
    {
        return $this->telegram;
    }

    /**
     * Get usage
     *
     * @return string
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get Show in Help
     *
     * @return bool
     */
    public function showInHelp()
    {
        return $this->show_in_help;
    }

    /**
     * Check if command is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * If this command is intended for private chats only.
     *
     * @return bool
     */
    public function isPrivateOnly()
    {
        return $this->private_only;
    }

    /**
     * If this is a SystemCommand
     *
     * @return bool
     */
    public function isSystemCommand()
    {
        return ($this instanceof SystemCommand);
    }

    /**
     * If this is an AdminCommand
     *
     * @return bool
     */
    public function isAdminCommand()
    {
        return ($this instanceof AdminCommand);
    }

    /**
     * If this is a UserCommand
     *
     * @return bool
     */
    public function isUserCommand()
    {
        return ($this instanceof UserCommand);
    }

    /**
     * Delete the current message if it has been called in a non-private chat.
     *
     * @return bool
     */
    protected function removeNonPrivateMessage()
    {
        $message = $this->getMessage() ?: $this->getEditedMessage();

        if ($message) {
            $chat = $message->getChat();

            if (!$chat->isPrivateChat()) {
                // Delete the falsely called command message.
                Request::deleteMessage([
                    'chat_id'    => $chat->getId(),
                    'message_id' => $message->getMessageId(),
                ]);

                return true;
            }
        }

        return false;
    }

    /**
     * Helper to reply to a chat directly.
     *
     * @param string $text
     * @param array  $data
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function replyToChat($text, array $data = [])
    {
        if ($message = $this->getMessage() ?: $this->getEditedMessage() ?: $this->getChannelPost() ?: $this->getEditedChannelPost()) {
            return Request::sendMessage(array_merge([
                'chat_id' => $message->getChat()->getId(),
                'text'    => $text,
            ], $data));
        }

        return Request::emptyResponse();
    }

    /**
     * Helper to reply to a user directly.
     *
     * @param string $text
     * @param array  $data
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function replyToUser($text, array $data = [])
    {
        if ($message = $this->getMessage() ?: $this->getEditedMessage()) {
            return Request::sendMessage(array_merge([
                'chat_id' => $message->getFrom()->getId(),
                'text'    => $text,
            ], $data));
        }

        return Request::emptyResponse();
    }
}
