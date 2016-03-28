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
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\User;

/**
 * Abstract Command Class
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
     * @var Entities\Update
     */
    protected $update;

    /**
     * Message object
     *
     * @var Entities\Message
     */
    protected $message;

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

    /**
     * Command config
     *
     * @var array
     */
    protected $config = [];

    /**
     * Constructor
     *
     * @param Telegram        $telegram
     * @param Entities\Update $update
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
     * @param Entities\Update $update
     * @return Command
     */
    public function setUpdate(Update $update = null)
    {
        if (!empty($update)) {
            $this->update = $update;
            $this->message = $this->update->getMessage();
        }
        return $this;
    }

    /**
     * Pre-execute command
     *
     * @return Entities\ServerResponse
     */
    public function preExecute()
    {
        if ($this->need_mysql && !($this->telegram->isDbEnabled() && DB::isDbConnected())) {
            return $this->executeNoDb();
        }
        return $this->execute();
    }

    /**
     * Execute command
     *
     * @return Entities\ServerResponse
     */
    abstract public function execute();

    /**
     * Execution if MySQL is required but not available
     *
     * @return Entities\ServerResponse
     */
    public function executeNoDb()
    {
        //Preparing message
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Sorry no database connection, unable to execute "' . $this->name . '" command.',
        ];

        return Request::sendMessage($data);
    }

    /**
     * Get update object
     *
     * @return Entities\Update
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * Get message object
     *
     * @return Entities\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get command config
     *
     * Look for config $name if found return it, if not return null.
     * If $name is not set return all set config.
     *
     * @param string|null $name
     *
     * @return mixed
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
     * Check if command is enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
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
}
