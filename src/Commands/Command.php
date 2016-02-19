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
     * Command
     *
     * @var string
     */
    protected $command;

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
    protected $description = 'Command help';

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
    protected $config;

    /**
     * Constructor
     *
     * @param Telegram $telegram
     */
    public function __construct(Telegram $telegram)
    {
        $this->telegram = $telegram;
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
     * @return mixed
     */
    public function preExecute()
    {
        if (!$this->need_mysql || ($this->telegram->isDbEnabled() && DB::isDbConnected())) {
            return $this->execute();
        }
        return $this->executeNoDB();
    }

    /**
     * Execute command
     */
    abstract public function execute();

    /**
     * Execution if MySQL is required but not available
     *
     * @return boolean
     */
    public function executeNoDB()
    {
        //Preparing message
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Sorry no database connection, unable to execute "' . $this->name . '" command.',
        ];

        return Request::sendMessage($data)->isOk();
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
     * Look for parameter $name if found return it, if not return null.
     * If $name is not set return the all set params
     *
     * @param string|null $name
     * @return mixed
     */
    public function getConfig($name = null)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return null;
        }

        return $this->config;
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
     * Set command
     *
     * @param string $command
     * @return Command
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
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
}
