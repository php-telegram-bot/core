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

defined('TB_BASE_PATH') || define('TB_BASE_PATH', __DIR__);
defined('TB_BASE_COMMANDS_PATH') || define('TB_BASE_COMMANDS_PATH', TB_BASE_PATH . '/Commands');

use Exception;
use Illuminate\Container\Container;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Console\Kernel as ConsoleKernel;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Extensions\Botan\Botan;
use Longman\TelegramBot\Http\Client;
use Longman\TelegramBot\Http\Kernel;
use Longman\TelegramBot\Http\Request;
use Longman\TelegramBot\Http\Response;
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
    const VERSION = '0.53.0';

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
     * Container
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

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
     * ServerResponse of the last Command execution
     *
     * @var \Longman\TelegramBot\Http\Response
     */
    protected $last_command_response;

    /**
     * Check if runCommands() is running in this session
     *
     * @var boolean
     */
    protected $run_commands = false;

    /**
     * Is running getUpdates without DB enabled
     *
     * @var bool
     */
    public $getupdates_without_database = false;

    /**
     * Last update ID
     * Only used when running getUpdates without a database
     *
     * @var integer
     */
    public $last_update_id = null;

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
        if (! isset($matches[1])) {
            throw new TelegramException('Invalid API KEY defined!');
        }
        $this->bot_id = $matches[1];
        $this->api_key = $api_key;

        if (! empty($bot_username)) {
            $this->bot_username = $bot_username;
        }

        $this->registerContainer();

        $this->initializeConfig();

        // Add default system commands path
        $this->addCommandsPath(TB_BASE_COMMANDS_PATH . '/SystemCommands');

        Client::initialize($this);
    }

    /**
     * Register the container.
     *
     * @return void
     */
    protected function registerContainer()
    {
        $this->container = Container::getInstance();

        $this->container->instance(Telegram::class, $this);
    }

    /**
     * Initialize config.
     *
     * @return void
     */
    protected function initializeConfig()
    {
        $config = new Config();

        $this->container->instance(Config::class, $config);
    }

    /**
     * Get config.
     *
     * @return \Longman\TelegramBot\Config
     */
    public function getConfig()
    {
        return $this->container->make(Config::class);
    }

    /**
     * Get container instance.
     *
     * @return \Illuminate\Container\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Initialize Database connection
     *
     * @param array $credential
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
     * @param PDO $external_pdo_connection PDO database object
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

        $command_paths = $this->getConfig()->getCommandsPaths();
        foreach ($command_paths as $path) {
            try {
                // Get all "*Command.php" files
                $files = new RegexIterator(
                    new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($path)
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

                    $command_obj = $this->createCommandObject($command);
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
    public function createCommandObject($command)
    {
        $which = ['System'];
        if ($this->isAdmin()) {
            $which[] = 'Admin';
        }
        $which[] = 'User';

        $command_name = $this->ucfirstUnicode($command);
        foreach ($which as $auth) {
            $command_namespace = __NAMESPACE__ . '\\Commands\\' . $auth . 'Commands\\' . $command_name . 'Command';

            if (class_exists($command_namespace)) {
                return new $command_namespace($this, $this->update);
            }
        }

        return null;
    }

    /**
     * Get the ServerResponse of the last Command execution
     *
     * @return \Longman\TelegramBot\Http\Response
     */
    public function getLastCommandResponse()
    {
        return $this->last_command_response;
    }

    /**
     * Handle getUpdates method
     *
     * @param \Longman\TelegramBot\Http\Request|null $request
     * @param int|null $limit
     * @param int|null $timeout
     *
     * @return \Longman\TelegramBot\Http\Response
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handleGetUpdates(Request $request = null, $limit = null, $timeout = null)
    {
        if (empty($this->bot_username)) {
            throw new TelegramException('Bot Username is not defined!');
        }

        /** @var \Longman\TelegramBot\Console\Kernel $kernel */
        $kernel = $this->getContainer()->make(ConsoleKernel::class);

        if (is_null($request)) {
            $request = Request::capture();
        }

        $this->container->instance(Request::class, $request);

        $response = $kernel->handle($request, $limit, $timeout);

        return $response;
    }

    /**
     * Handle bot request from webhook
     *
     * @param \Longman\TelegramBot\Http\Request|null $request
     * @return \Longman\TelegramBot\Http\Response
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handle(Request $request = null)
    {
        if (empty($this->getBotUsername())) {
            throw new TelegramException('Bot Username is not defined!');
        }

        /** @var \Longman\TelegramBot\Http\Kernel $kernel */
        $kernel = $this->getContainer()->make(Kernel::class);

        if (is_null($request)) {
            $request = Request::capture();
        }

        $this->container->instance(Request::class, $request);

        $response = $kernel->handle($request);

        return $response;
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
     * @return \Longman\TelegramBot\Http\Response
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function processUpdate(Update $update)
    {
        $this->update = $update;
        $this->last_update_id = $update->getUpdateId();

        // If all else fails, it's a generic message.
        $command = 'genericmessage';

        $update_type = $update->getUpdateType();
        if ($update_type === Update::TYPE_MESSAGE) {
            $message = $update->getMessage();

            // Load admin commands
            if ($this->isAdmin()) {
                $this->addCommandsPath(TB_BASE_COMMANDS_PATH . '/AdminCommands', false);
            }

            $type = $message->getType();
            if ($type === 'command') {
                $command = $message->getCommand();
            } else if (in_array($type, [
                'new_chat_members',
                'left_chat_member',
                'new_chat_title',
                'new_chat_photo',
                'delete_chat_photo',
                'group_chat_created',
                'supergroup_chat_created',
                'channel_chat_created',
                'migrate_to_chat_id',
                'migrate_from_chat_id',
                'pinned_message',
                'invoice',
                'successful_payment',
            ], true)
            ) {
                $command = $this->getCommandFromType($type);
            }
        } else {
            $command = $this->getCommandFromType($update_type);
        }

        // Make sure we have an up-to-date command list
        // This is necessary to "require" all the necessary command files!
        $this->getCommandsList();

        // Make sure we don't try to process update that was already processed
        $last_id = DB::selectTelegramUpdate(1, $update->getUpdateId());
        if ($last_id && count($last_id) === 1) {
            TelegramLog::debug('Duplicate update received, processing aborted!');

            return new Response(['ok' => true, 'result' => true]);
        }

        DB::insertRequest($update);

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
        $command = strtolower($command);
        $command_obj = $this->createCommandObject($command);

        if (! $command_obj || ! $command_obj->isEnabled()) {
            // Failsafe in case the Generic command can't be found
            if ($command === 'generic') {
                throw new TelegramException('Generic command missing!');
            }

            // Handle a generic command or non existing one
            $this->last_command_response = $this->executeCommand('generic');
        } else {
            // Botan.io integration, make sure only the actual command user executed is reported
            if (Botan::isEnabled()) {
                Botan::lock($command);
            }

            // execute() method is executed after preExecute()
            // This is to prevent executing a DB query without a valid connection
            $this->last_command_response = $command_obj->preExecute();

            // Botan.io integration, send report after executing the command
            if (Botan::isEnabled()) {
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
        $this->getConfig()->addAdmin($admin_id);

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
        $this->getConfig()->addAdmins($admin_ids);

        return $this;
    }

    /**
     * Get list of admins
     *
     * @return array
     */
    public function getAdminList()
    {
        return $this->getConfig()->getAdmins();
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

        $admins = $this->getConfig()->getAdmins();
        return ($user_id === null) ? false : in_array($user_id, $admins, true);
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
     * @param string $path Custom commands path to add
     * @param bool $before If the path should be prepended or appended to the list
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function addCommandsPath($path, $before = true)
    {
        $this->getConfig()->addCommandsPath($path, $before);

        return $this;
    }

    /**
     * Add multiple custom commands paths
     *
     * @param array $paths Custom commands paths to add
     * @param bool $before If the paths should be prepended or appended to the list
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function addCommandsPaths(array $paths, $before = true)
    {
        $this->getConfig()->addCommandsPaths($paths, $before);

        return $this;
    }

    /**
     * Return the list of commands paths
     *
     * @return array
     */
    public function getCommandsPaths()
    {

        return $this->getConfig()->getCommandsPaths();
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
     * @param array $config
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
        return self::VERSION;
    }

    /**
     * Set Webhook for bot
     *
     * @param string $url
     * @param array $parameters Optional parameters.
     *
     * @return \Longman\TelegramBot\Http\Response
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function setWebhook($url, array $parameters = [])
    {
        if (empty($url)) {
            throw new TelegramException('Hook url is empty!');
        }

        $parameters = array_intersect_key($parameters, array_flip([
            'certificate',
            'max_connections',
            'allowed_updates',
        ]));
        $parameters['url'] = $url;

        // If the certificate is passed as a path, encode and add the file to the data array.
        if (! empty($parameters['certificate']) && is_string($parameters['certificate'])) {
            $parameters['certificate'] = Client::encodeFile($parameters['certificate']);
        }

        $result = Client::setWebhook($parameters);

        if (! $result->isOk()) {
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
        $result = Client::deleteWebhook();

        if (! $result->isOk()) {
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
        return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
            . mb_strtolower(mb_substr($str, 1, mb_strlen($str), $encoding), $encoding);
    }

    /**
     * Enable Botan.io integration
     *
     * @param  string $token
     * @param  array $options
     *
     * @return \Longman\TelegramBot\Telegram
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function enableBotan($token, array $options = [])
    {
        Botan::initializeBotan($token, $options);
        Botan::setEnabled(true);

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
        Client::setLimiter(true, $options);

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
        if (! is_array($commands) || empty($commands)) {
            throw new TelegramException('No command(s) provided!');
        }

        $this->run_commands = true;
        Botan::setEnabled(false);   // Force disable Botan.io integration, we don't want to track self-executed commands!

        $result = Client::getMe();

        if ($result->isOk()) {
            $result = $result->getResult();

            $bot_id = $result->getId();
            $bot_name = $result->getFirstName();
            $bot_username = $result->getUsername();
        } else {
            $bot_id = $this->getBotId();
            $bot_name = $this->getBotUsername();
            $bot_username = $this->getBotUsername();
        }

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

    /**
     * Switch to enable running getUpdates without a database
     *
     * @param bool $enable
     */
    public function useGetUpdatesWithoutDatabase($enable = true)
    {
        $this->getupdates_without_database = $enable;
    }

    /**
     * Return last update id
     *
     * @return int
     */
    public function getLastUpdateId()
    {
        return $this->last_update_id;
    }
}
