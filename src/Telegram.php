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

define('BASE_PATH', __DIR__);
define('BASE_COMMANDS_PATH', BASE_PATH . '/Commands');

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @package         Telegram
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class Telegram
{
    /**
     * Version
     *
     * @var string
     */
    protected $version = '0.28.0';

    /**
     * Telegram API key
     *
     * @var string
     */
    protected $api_key = '';

    /**
     * Telegram Bot name
     *
     * @var string
     */
    protected $bot_name = '';

    /**
     * Raw request data (json) for webhook methods
     *
     * @var string
     */
    protected $input;

    /**
     * Custom commands paths
     *
     * @var array
     */
    protected $commands_paths = [];

    /**
     * Current Update object
     *
     * @var Entities\Update
     */
    protected $update;

    /**
     * Log verbose curl output
     *
     * @var bool
     */
    protected $log_requests;

    /**
     * Log path
     *
     * @var string
     */
    protected $log_path;

    /**
     * Upload path
     *
     * @var string
     */
    protected $upload_path;

    /**
     * Download path
     *
     * @var string
     */
    protected $download_path;

    /**
     * Log verbosity
     *
     * @var int
     */
    protected $log_verbosity;

    /**
     * MySQL integration
     *
     * @var boolean
     */
    protected $mysql_enabled = false;

    /**
     * PDO object
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * Commands config
     *
     * @var array
     */
    protected $commands_config = [];

    /**
     * Admins list
     *
     * @var array
     */
    protected $admins_list = [];

    /**
     * Constructor
     *
     * @param string $api_key
     * @param string $bot_name
     */
    public function __construct($api_key, $bot_name)
    {
        if (empty($api_key)) {
            throw new TelegramException('API KEY not defined!');
        }

        if (empty($bot_name)) {
            throw new TelegramException('Bot Username not defined!');
        }

        $this->api_key = $api_key;
        $this->bot_name = $bot_name;

        //Set default download and upload path
        $this->setDownloadPath(BASE_PATH . '/../Download');
        $this->setUploadPath(BASE_PATH . '/../Upload');

        //Add default system commands path
        $this->addCommandsPath(BASE_COMMANDS_PATH . '/SystemCommands');

        Request::initialize($this);
    }

    /**
     * Initialize
     *
     * @param array $credential
     * @param string $table_prefix
     */
    public function enableMySQL(array $credential, $table_prefix = null)
    {
        $this->pdo = DB::initialize($credential, $this, $table_prefix);
        $this->mysql_enabled = true;
    }

    /**
     * Get commands list
     *
     * @return array $commands
     */
    public function getCommandsList()
    {
        $commands = [];

        foreach ($this->commands_paths as $path) {
            try {
                //Get all "*Command.php" files
                $files = new \RegexIterator(
                    new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($path)
                    ),
                    '/^.+Command.php$/'
                );

                foreach ($files as $file) {
                    //Remove "Command.php" from filename
                    $command = $this->sanitizeCommand(substr($file->getFilename(), 0, -11));
                    $command_name = strtolower($command);

                    if (array_key_exists($command_name, $commands)) {
                        continue;
                    }

                    require_once $file->getPathname();

                    $command_obj = $this->getCommandObject($command);
                    if ($command_obj instanceof Commands\Command) {
                        $commands[$command_name] = $command_obj;
                    }
                }
            } catch (\Exception $e) {
                throw new TelegramException('Error getting commands from path: ' . $path);
            }
        }

        return $commands;
    }

    /**
     * Get an object instance of the passed command
     *
     * @param string $command
     *
     * @return Entities\Command|null
     */
    public function getCommandObject($command)
    {
        $which = ['System'];
        ($this->isAdmin()) && $which[] = 'Admin';
        $which[] = 'User';

        foreach ($which as $auth) {
            $command_namespace = __NAMESPACE__ . '\\Commands\\' . $auth . 'Commands\\' . ucfirst($command) . 'Command';
            if (class_exists($command_namespace)) {
                return new $command_namespace($this, $this->update);
            }
        }

        return null;
    }

    /**
     * Set log requests
     *
     * 0 don't store
     * 1 store the Curl verbose output with Telegram updates
     *
     * @param bool $log_requests
     *
     * @return Telegram
     */
    public function setLogRequests($log_requests)
    {
        $this->log_requests = $log_requests;
        //Set default log verbosity
        $this->log_verbosity = 1;
        return $this;
    }

    /**
     * Get log requests
     *
     * @return bool
     */
    public function getLogRequests()
    {
        return $this->log_requests;
    }

    /**
     * Set log path
     *
     * @param string $log_path
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function setLogPath($log_path)
    {
        $this->log_path = $log_path;
        return $this;
    }

    /**
     * Get log path
     *
     * @return string
     */
    public function getLogPath()
    {
        return $this->log_path;
    }

    /**
     * Set log Verbosity
     *
     * @param int $log_verbosity
     *
     * 1 only incoming updates from webhook and getUpdates
     * 3 incoming updates from webhook and getUpdates and curl request info and response
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function setLogVerbosity($log_verbosity)
    {
        $this->log_verbosity = $log_verbosity;
        return $this;
    }

    /**
     * Get log verbosity
     *
     * @return int
     */
    public function getLogVerbosity()
    {
        return $this->log_verbosity;
    }

    /**
     * Set custom input string for debug purposes
     *
     * @param string $input (json format)
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function setCustomInput($input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Get custom input string for debug purposes
     *
     * @return string
     */
    public function getCustomInput()
    {
        return $this->input;
    }

    /**
     * Handle getUpdates method
     *
     * @param int|null $limit
     * @param int|null $timeout
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function handleGetUpdates($limit = null, $timeout = null)
    {
        //DB Query
        $last_update = DB::selectTelegramUpdate(1);

        if (isset($last_update[0]['id'])) {
            //As explained in the telegram bot api documentation
            $offset = $last_update[0]['id']+1;
        } else {
            $offset = null;
        }

        $ServerResponse = Request::getUpdates([
            'offset' => $offset ,
            'limit' => $limit,
            'timeout' => $timeout
        ]);

        if ($ServerResponse->isOk()) {
            $results = '';
            $n_update = count($ServerResponse->getResult());
            for ($a = 0; $a < $n_update; $a++) {
                $result = $this->processUpdate($ServerResponse->getResult()[$a]);
            }
        }

        return $ServerResponse;
    }

    /**
     * Handle bot request from webhook
     *
     * @todo Should return the executed command result (true|false) but we shoud check if all commands return a value.
     * Furthermore this function is the twin of handleGetUpdates for webhook, but the first returns the ServerResponse
     * instead the latter return if the command has failed or not (true|false).
     * We shoud use the same convention for both.
     *
     * @return bool
     */
    public function handle()
    {
        $this->input = Request::getInput();

        if (empty($this->input)) {
            throw new TelegramException('Input is empty!');
        }
        $post = json_decode($this->input, true);
        if (empty($post)) {
            throw new TelegramException('Invalid JSON!');
        }

        $this->update = new Update($post, $this->bot_name);
        return $this->processUpdate();
    }

    /**
     * Get the command name from the command type
     *
     * @param string $type
     *
     * @return string
     */
    private function getCommandFromType($type)
    {
        return ucfirst(str_replace('_', '', $type));
    }

    /**
     * Process Handle bot request
     *
     * @return bool
     */
    public function processUpdate()
    {
        $update_type = $this->update->getUpdateType();

        //If all else fails, it's a generic message.
        $command = 'genericmessage';

        if (in_array($update_type, ['inline_query', 'chosen_inline_result'])) {
            $command = $this->getCommandFromType($update_type);
        } elseif ($update_type === 'message') {
            $message = $this->update->getMessage();

            //Load admin commands
            if ($this->isAdmin($message->getFrom()->getId())) {
                $this->addCommandsPath(BASE_COMMANDS_PATH . '/AdminCommands', false);
            }

            $this->addCommandsPath(BASE_COMMANDS_PATH . '/UserCommands', false);

            $type = $message->getType();
            if ($type === 'command') {
                $command = $message->getCommand();
            } elseif (in_array($type, [
                'channel_chat_created',
                'delete_chat_photo',
                'group_chat_created',
                'left_chat_participant',
                'new_chat_participant',
                'new_chat_title',
                'supergroup_chat_created',
            ])) {
                $command = $this->getCommandFromType($type);
            }
        }

        //Make sure we have an up-to-date command list
        //This is necessary to "require" all the necessary command files!
        $this->getCommandsList();

        DB::insertRequest($this->update);

        return $this->executeCommand($command);
    }

    /**
     * Execute /command
     *
     * @param string $command
     *
     * @return mixed
     */
    public function executeCommand($command)
    {
        $command_obj = $this->getCommandObject($command);

        if (!$command_obj || !$command_obj->isEnabled()) {
            //handle a generic command or non existing one
            return $this->executeCommand('Generic');
        }

        //execute() methods will be execute after preexecute() methods
        //this for prevent to execute db query without connection
        return $command_obj->preExecute();
    }

    /**
     * @todo Complete DocBlock
     */
    protected function sanitizeCommand($command)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $command)));
    }

    /**
     * Enable Admin Account
     *
     * @param array $admins_list List of admins
     *
     * @return string
     */
    public function enableAdmins(array $admins_list)
    {
        foreach ($admins_list as $admin) {
            if ($admin > 0) {
                $this->admins_list[] = $admin;
            } else {
                throw new TelegramException('Invalid value "' . $admin . '" for admin!');
            }
        }

        return $this;
    }

    /**
     * Get list of admins
     *
     * @return array
     */
    public function getAdminList()
    {
        return $this->admins_list;
    }

    /**
     * Check if the passed user is an admin
     *
     * If no user id is passed, the current update is checked for a valid message sender.
     *
     * @param int|null $user_id
     *
     * @return bool
     */
    public function isAdmin($user_id = null)
    {
        if ($user_id === null && $this->update !== null) {
            if (($message = $this->update->getMessage()) && ($from = $message->getFrom())) {
                $user_id = $from->getId();
            }
        }

        return ($user_id === null) ? false : in_array($user_id, $this->admins_list);
    }

    /**
     * Check if user required the db connection
     *
     * @return bool
     */
    public function isDbEnabled()
    {
        if ($this->mysql_enabled) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add custom commands path
     *
     * @param string $path   Custom commands path
     * @param bool   $before If the path should be prepended or appended to the list
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function addCommandsPath($path, $before = true)
    {
        if (!is_dir($path)) {
            throw new TelegramException('Commands path "' . $path . '" does not exist!');
        }
        if (!in_array($path, $this->commands_paths)) {
            if ($before) {
                array_unshift($this->commands_paths, $path);
            } else {
                array_push($this->commands_paths, $path);
            }
        }
        return $this;
    }

    /**
     * Set custom upload path
     *
     * @param string $path Custom upload path
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function setUploadPath($path)
    {
        $this->upload_path = $path;
        return $this;
    }

    /**
     * Get custom upload path
     *
     * @return string
     */
    public function getUploadPath()
    {
        return $this->upload_path;
    }

    /**
     * Set custom download path
     *
     * @param string $path Custom download path
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function setDownloadPath($path)
    {
        $this->download_path = $path;
        return $this;
    }

    /**
     * Get custom download path
     *
     * @return string
     */
    public function getDownloadPath()
    {
        return $this->download_path;
    }

    /**
     * Set command config
     *
     * Provide further variables to a particular commands.
     * For example you can add the channel name at the command /sendtochannel
     * Or you can add the api key for external service.
     *
     * @param string $command
     * @param array  $config
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function setCommandConfig($command, array $config)
    {
        $this->commands_config[$command] = $config;
        return $this;
    }

    /**
     * Get command config
     *
     * @param string $command
     *
     * @return array
     */
    public function getCommandConfig($command)
    {
        return isset($this->commands_config[$command]) ? $this->commands_config[$command] : [];
    }

    /**
     * Get API key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Get Bot name
     *
     * @return string
     */
    public function getBotName()
    {
        return $this->bot_name;
    }

    /**
     * Get Version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set Webhook for bot
     *
     * @param string       $url
     * @param string|null  $path_certificate
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function setWebHook($url, $path_certificate = null)
    {
        if (empty($url)) {
            throw new TelegramException('Hook url is empty!');
        }

        $result = Request::setWebhook($url, $path_certificate);

        if (!$result->isOk()) {
            throw new TelegramException(
                'Webhook was not set! Error: ' . $result->getErrorCode() . ' ' . $result->getDescription()
            );
        }

        return $result;
    }

    /**
     * Unset Webhook for bot
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function unsetWebHook()
    {
        $result = Request::setWebhook();

        if (!$result->isOk()) {
            throw new TelegramException(
                'Webhook was not unset! Error: ' . $result->getErrorCode() . ' ' . $result->getDescription()
            );
        }

        return $result;
    }
}
