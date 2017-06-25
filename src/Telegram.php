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

use Exception;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use PDO;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Telegram
{
    /**
     * Version
     *
     * @var string
     */
    protected $version = '0.45.0';

    /**
     * Telegram API key
     *
     * @var string
     */
    protected $api_key = '';

    /**
     * Telegram Bot username
     *
     * @var string
     */
    protected $bot_username = '';

    /**
     * Telegram Bot id
     *
     * @var string
     */
    protected $bot_id = '';

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
     * @var \Longman\TelegramBot\Entities\Update
     */
    protected $update;

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
     * ServerResponse of the last Command execution
     *
     * @var \Longman\TelegramBot\Entities\ServerResponse
     */
    protected $last_command_response;

    /**
     * Botan.io integration
     *
     * @var boolean
     */
    protected $botan_enabled = false;

    /**
     * Check if runCommands() is running in this session
     *
     * @var boolean
     */
    protected $run_commands = false;

    /**
     * Telegram constructor.
     *
     * @param string $api_key
     * @param string $bot_username
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct($api_key, $bot_username = '')
    {
        if (empty($api_key)) {
            throw new TelegramException('API KEY not defined!');
        }
        preg_match('/(\d+)\:[\w\-]+/', $api_key, $matches);
        if (!isset($matches[1])) {
            throw new TelegramException('Invalid API KEY defined!');
        }
        $this->bot_id  = $matches[1];
        $this->api_key = $api_key;

        if (!empty($bot_username)) {
            $this->bot_username = $bot_username;
        }

        //Add default system commands path
        $this->addCommandsPath(BASE_COMMANDS_PATH . '/SystemCommands');

        Request::initialize($this);
    }

    /**
     * Initialize Database connection
     *
     * @param array  $credential
     * @param string $table_prefix
     * @param string $encoding
     *
     * @return \Longman\TelegramBot\Telegram
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function enableMySql(array $credential, $table_prefix = null, $encoding = 'utf8mb4')
    {
        $this->pdo = DB::initialize($credential, $this, $table_prefix, $encoding);
        ConversationDB::initializeConversation();
        $this->mysql_enabled = true;

        return $this;
    }

    /**
     * Initialize Database external connection
     *
     * @param PDO    $external_pdo_connection PDO database object
     * @param string $table_prefix
     *
     * @return \Longman\TelegramBot\Telegram
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function enableExternalMySql($external_pdo_connection, $table_prefix = null)
    {
        $this->pdo = DB::externalInitialize($external_pdo_connection, $this, $table_prefix);
        ConversationDB::initializeConversation();
        $this->mysql_enabled = true;

        return $this;
    }

    /**
     * Get commands list
     *
     * @return array $commands
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function getCommandsList()
    {
        $commands = [];

        foreach ($this->commands_paths as $path) {
            try {
                //Get all "*Command.php" files
                $files = new RegexIterator(
                    new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($path)
                    ),
                    '/^.+Command.php$/'
                );

                foreach ($files as $file) {
                    //Remove "Command.php" from filename
                    $command      = $this->sanitizeCommand(substr($file->getFilename(), 0, -11));
                    $command_name = strtolower($command);

                    if (array_key_exists($command_name, $commands)) {
                        continue;
                    }

                    require_once $file->getPathname();

                    $command_obj = $this->getCommandObject($command);
                    if ($command_obj instanceof Command) {
                        $commands[$command_name] = $command_obj;
                    }
                }
            } catch (Exception $e) {
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
     * @return \Longman\TelegramBot\Commands\Command|null
     */
    public function getCommandObject($command)
    {
        $which = ['System'];
        $this->isAdmin() && $which[] = 'Admin';
        $which[] = 'User';

        foreach ($which as $auth) {
            $command_namespace = __NAMESPACE__ . '\\Commands\\' . $auth . 'Commands\\' . $this->ucfirstUnicode($command) . 'Command';
            if (class_exists($command_namespace)) {
                return new $command_namespace($this, $this->update);
            }
        }

        return null;
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
     * Get the ServerResponse of the last Command execution
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function getLastCommandResponse()
    {
        return $this->last_command_response;
    }

    /**
     * Handle getUpdates method
     *
     * @param int|null $limit
     * @param int|null $timeout
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handleGetUpdates($limit = null, $timeout = null)
    {
        if (empty($this->bot_username)) {
            throw new TelegramException('Bot Username is not defined!');
        }

        if (!DB::isDbConnected()) {
            return new ServerResponse(
                [
                    'ok'          => false,
                    'description' => 'getUpdates needs MySQL connection!',
                ],
                $this->bot_username
            );
        }

        //Take custom input into account.
        if ($custom_input = $this->getCustomInput()) {
            $response = new ServerResponse(json_decode($custom_input, true), $this->bot_username);
        } else {
            //DB Query
            $last_update = DB::selectTelegramUpdate(1);
            $last_update = reset($last_update);

            //As explained in the telegram bot api documentation
            $offset = isset($last_update['id']) ? $last_update['id'] + 1 : null;

            $response = Request::getUpdates(
                [
                    'offset'  => $offset,
                    'limit'   => $limit,
                    'timeout' => $timeout,
                ]
            );
        }

        if ($response->isOk()) {
            //Process all updates
            /** @var Update $result */
            foreach ((array) $response->getResult() as $result) {
                $this->processUpdate($result);
            }
        }

        return $response;
    }

    /**
     * Handle bot request from webhook
     *
     * @return bool
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handle()
    {
        if (empty($this->bot_username)) {
            throw new TelegramException('Bot Username is not defined!');
        }

        $this->input = Request::getInput();

        if (empty($this->input)) {
            throw new TelegramException('Input is empty!');
        }

        $post = json_decode($this->input, true);
        if (empty($post)) {
            throw new TelegramException('Invalid JSON!');
        }

        if ($response = $this->processUpdate(new Update($post, $this->bot_username))) {
            return $response->isOk();
        }

        return false;
    }

    /**
     * Get the command name from the command type
     *
     * @param string $type
     *
     * @return string
     */
    protected function getCommandFromType($type)
    {
        return $this->ucfirstUnicode(str_replace('_', '', $type));
    }

    /**
     * Process bot Update request
     *
     * @param \Longman\TelegramBot\Entities\Update $update
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function processUpdate(Update $update)
    {
        $this->update = $update;

        //If all else fails, it's a generic message.
        $command = 'genericmessage';

        $update_type = $this->update->getUpdateType();
        if (in_array($update_type, ['edited_message', 'channel_post', 'edited_channel_post', 'inline_query', 'chosen_inline_result', 'callback_query'], true)) {
            $command = $this->getCommandFromType($update_type);
        } elseif ($update_type === 'message') {
            $message = $this->update->getMessage();

            //Load admin commands
            if ($this->isAdmin()) {
                $this->addCommandsPath(BASE_COMMANDS_PATH . '/AdminCommands', false);
            }

            $type = $message->getType();
            if ($type === 'command') {
                $command = $message->getCommand();
            } elseif (in_array($type, [
                'channel_chat_created',
                'delete_chat_photo',
                'group_chat_created',
                'left_chat_member',
                'migrate_from_chat_id',
                'migrate_to_chat_id',
                'new_chat_member',
                'new_chat_photo',
                'new_chat_title',
                'pinned_message',
                'supergroup_chat_created',
            ], true)
            ) {
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function executeCommand($command)
    {
        $command_obj = $this->getCommandObject($command);

        if (!$command_obj || !$command_obj->isEnabled()) {
            //Failsafe in case the Generic command can't be found
            if ($command === 'Generic') {
                throw new TelegramException('Generic command missing!');
            }

            //Handle a generic command or non existing one
            $this->last_command_response = $this->executeCommand('Generic');
        } else {
            //Botan.io integration, make sure only the actual command user executed is reported
            if ($this->botan_enabled) {
                Botan::lock($command);
            }

            //execute() method is executed after preExecute()
            //This is to prevent executing a DB query without a valid connection
            $this->last_command_response = $command_obj->preExecute();

            //Botan.io integration, send report after executing the command
            if ($this->botan_enabled) {
                Botan::track($this->update, $command);
            }
        }

        return $this->last_command_response;
    }

    /**
     * Sanitize Command
     *
     * @param string $command
     *
     * @return string
     */
    protected function sanitizeCommand($command)
    {
        return str_replace(' ', '', $this->ucwordsUnicode(str_replace('_', ' ', $command)));
    }

    /**
     * Enable a single Admin account
     *
     * @param integer $admin_id Single admin id
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function enableAdmin($admin_id)
    {
        if (is_int($admin_id) && $admin_id > 0 && !in_array($admin_id, $this->admins_list, true)) {
            $this->admins_list[] = $admin_id;
        } else {
            TelegramLog::error('Invalid value "%s" for admin.', $admin_id);
        }

        return $this;
    }

    /**
     * Enable a list of Admin Accounts
     *
     * @param array $admin_ids List of admin ids
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function enableAdmins(array $admin_ids)
    {
        foreach ($admin_ids as $admin_id) {
            $this->enableAdmin($admin_id);
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
            //Try to figure out if the user is an admin
            $update_methods = [
                'getMessage',
                'getEditedMessage',
                'getChannelPost',
                'getEditedChannelPost',
                'getInlineQuery',
                'getChosenInlineResult',
                'getCallbackQuery',
            ];
            foreach ($update_methods as $update_method) {
                $object = call_user_func([$this->update, $update_method]);
                if ($object !== null && $from = $object->getFrom()) {
                    $user_id = $from->getId();
                    break;
                }
            }
        }

        return ($user_id === null) ? false : in_array($user_id, $this->admins_list, true);
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
     * Add a single custom commands path
     *
     * @param string $path   Custom commands path to add
     * @param bool   $before If the path should be prepended or appended to the list
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function addCommandsPath($path, $before = true)
    {
        if (!is_dir($path)) {
            TelegramLog::error('Commands path "%s" does not exist.', $path);
        } elseif (!in_array($path, $this->commands_paths, true)) {
            if ($before) {
                array_unshift($this->commands_paths, $path);
            } else {
                $this->commands_paths[] = $path;
            }
        }

        return $this;
    }

    /**
     * Add multiple custom commands paths
     *
     * @param array $paths  Custom commands paths to add
     * @param bool  $before If the paths should be prepended or appended to the list
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function addCommandsPaths(array $paths, $before = true)
    {
        foreach ($paths as $path) {
            $this->addCommandsPath($path, $before);
        }

        return $this;
    }

    /**
     * Return the list of commands paths
     *
     * @return array
     */
    public function getCommandsPaths()
    {
        return $this->commands_paths;
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
    public function getBotUsername()
    {
        return $this->bot_username;
    }

    /**
     * Get Bot Id
     *
     * @return string
     */
    public function getBotId()
    {
        return $this->bot_id;
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
     * @param string $url
     * @param array  $data Optional parameters.
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function setWebhook($url, array $data = [])
    {
        if (empty($url)) {
            throw new TelegramException('Hook url is empty!');
        }

        $result = Request::setWebhook($url, $data);

        if (!$result->isOk()) {
            throw new TelegramException(
                'Webhook was not set! Error: ' . $result->getErrorCode() . ' ' . $result->getDescription()
            );
        }

        return $result;
    }

    /**
     * Delete any assigned webhook
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function deleteWebhook()
    {
        $result = Request::deleteWebhook();

        if (!$result->isOk()) {
            throw new TelegramException(
                'Webhook was not deleted! Error: ' . $result->getErrorCode() . ' ' . $result->getDescription()
            );
        }

        return $result;
    }

    /**
     * Replace function `ucwords` for UTF-8 characters in the class definition and commands
     *
     * @param string $str
     * @param string $encoding (default = 'UTF-8')
     *
     * @return string
     */
    protected function ucwordsUnicode($str, $encoding = 'UTF-8')
    {
        return mb_convert_case($str, MB_CASE_TITLE, $encoding);
    }

    /**
     * Replace function `ucfirst` for UTF-8 characters in the class definition and commands
     *
     * @param string $str
     * @param string $encoding (default = 'UTF-8')
     *
     * @return string
     */
    protected function ucfirstUnicode($str, $encoding = 'UTF-8')
    {
        return
            mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
            . mb_strtolower(mb_substr($str, 1, mb_strlen($str), $encoding), $encoding);
    }

    /**
     * Enable Botan.io integration
     *
     * @param  string $token
     * @param  array  $options
     *
     * @return \Longman\TelegramBot\Telegram
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function enableBotan($token, array $options = [])
    {
        Botan::initializeBotan($token, $options);
        $this->botan_enabled = true;

        return $this;
    }

    /**
     * Enable requests limiter
     *
     * @param  array $options
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function enableLimiter(array $options = [])
    {
        Request::setLimiter(true, $options);

        return $this;
    }

    /**
     * Run provided commands
     *
     * @param array $commands
     *
     * @throws TelegramException
     */
    public function runCommands($commands)
    {
        if (!is_array($commands) || empty($commands)) {
            throw new TelegramException('No command(s) provided!');
        }

        $this->run_commands  = true;
        $this->botan_enabled = false;   // Force disable Botan.io integration, we don't want to track self-executed commands!

        $result = Request::getMe()->getResult();

        if (!$result->getId()) {
            throw new TelegramException('Received empty/invalid getMe result!');
        }

        $bot_id       = $result->getId();
        $bot_name     = $result->getFirstName();
        $bot_username = $result->getUsername();

        $this->enableAdmin($bot_id);    // Give bot access to admin commands
        $this->getCommandsList();       // Load full commands list

        foreach ($commands as $command) {
            $this->update = new Update(
                [
                    'update_id' => 0,
                    'message'   => [
                        'message_id' => 0,
                        'from'       => [
                            'id'         => $bot_id,
                            'first_name' => $bot_name,
                            'username'   => $bot_username,
                        ],
                        'date'       => time(),
                        'chat'       => [
                            'id'   => $bot_id,
                            'type' => 'private',
                        ],
                        'text'       => $command,
                    ],
                ]
            );

            $this->executeCommand($this->update->getMessage()->getCommand());
        }
    }

    /**
     * Is this session initiated by runCommands()
     *
     * @return bool
     */
    public function isRunCommands()
    {
        return $this->run_commands;
    }
}
