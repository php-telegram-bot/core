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
use Longman\TelegramBot\Entities\Update;

abstract class Command
{
    /**
     * Telegram object
     *
     * @var \Longman\TelegramBot\Telegram
     */
    protected $telegram;

    /**
     * Update object
     *
     * @var \Longman\TelegramBot\Entities\Update
     */
    protected $update;

    /**
     * Message object
     *
     * @var \Longman\TelegramBot\Entities\Message
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

    /**
     * Command config
     *
     * @var array
     */
    protected $config = [];

    /**
     * Constructor
     *
     * @param \Longman\TelegramBot\Telegram        $telegram
     * @param \Longman\TelegramBot\Entities\Update $update
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
     * @param \Longman\TelegramBot\Entities\Update $update
     *
     * @return \Longman\TelegramBot\Commands\Command
     */
    public function setUpdate(Update $update = null)
    {
        if ($update !== null) {
            $this->update  = $update;
            $this->message = $this->update->getMessage();
        }

        return $this;
    }

    /**
     * Pre-execute command
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    abstract public function execute();

    /**
     * Execution if MySQL is required but not available
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
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
     * @return \Longman\TelegramBot\Entities\Update
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * Get message object
     *
     * @return \Longman\TelegramBot\Entities\Message
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
     * @return \Longman\TelegramBot\Telegram
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
