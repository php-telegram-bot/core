<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot;

define('BASE_PATH', dirname(__FILE__));

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
    protected $version = '0.23.0';

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
     * Raw request data
     *
     * @var string
     */
    protected $input;

    /**
     * Custom commands folder
     *
     * @var array
     */
    protected $commands_dir = array();

    /**
     * Update object
     *
     * @var \Longman\TelegramBot\Entities\Update
     */
    protected $update;

    /**
     * Log Requests
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
     * Upload Path
     *
     * @var string
     */
    protected $upload_path;

    /**
     * Dowload Path
     *
     * @var string
     */
    protected $download_path;

    /**
     * Log verbosity
     *
     * @var string
     */
    protected $log_verbosity;

    /**
     * MySQL Integration
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
    protected $commands_config;

    /**
     * Message types
     *
     * @var array
     */
    protected $message_types = array('text', 'command', 'new_chat_participant',
        'left_chat_participant', 'new_chat_title', 'delete_chat_photo', 'group_chat_created'
        );

    /**
     * Admins List
     *
     * @var array
     */
    protected $admins_list = [];

    /**
     * Admin
     *
     * @var boolean
     */

    protected $admin_enabled = false;

    /**
     * Constructor
     *
     * @param string $api_key
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
        //Set default download and upload dir
        $this->setDownloadPath(BASE_PATH . "/../Download");
        $this->setUploadPath(BASE_PATH . "/../Upload");

        Request::initialize($this);
    }

     /**
     * Initialize
     *
     * @param array credential, string table_prefix
     */
    public function enableMySQL(array $credential, $table_prefix = null)
    {
        $this->pdo = DB::initialize($credential, $table_prefix);
        $this->mysql_enabled = true;
    }

    /**
     * Get commands list
     *
     * @return array $commands
     */
    public function getCommandsList()
    {

        $commands = array();
        try {
            $files = new \DirectoryIterator(BASE_PATH . '/Commands');
        } catch (\Exception $e) {
            throw new TelegramException('Can not open path: ' . BASE_PATH . '/Commands');
        }

        foreach ($files as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            $name = $fileInfo->getFilename();

            if (substr($name, -11, 11) === 'Command.php') {
                $name = strtolower(str_replace('Command.php', '', $name));
                $commands[$name] = $this->getCommandClass($name);
            }
        }

        if (!empty($this->commands_dir)) {
            foreach ($this->commands_dir as $dir) {
                if (!is_dir($dir)) {
                    continue;
                }

                foreach (new \DirectoryIterator($dir) as $fileInfo) {
                    if ($fileInfo->isDot()) {
                        continue;
                    }
                    $name = $fileInfo->getFilename();
                    if (substr($name, -11, 11) === 'Command.php') {
                        $name = strtolower(str_replace('Command.php', '', $name));
                        $commands[$name] = $this->getCommandClass($name);
                    }
                }
            }
        }
        return $commands;
    }

    /**
     * Set log requests
     *
     * @param bool $log_requests
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function setLogRequests($log_requests)
    {
        $this->log_requests = $log_requests;
        //set default log verbosity
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
     * @param string $log_path
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
     *
     * @return int
     */
    public function getLogVerbosity()
    {
        return $this->log_verbosity;
    }

    /**
     * Set custom update string for debug purposes
     *
     * @param string $update (json format)
     *
     * @return \Longman\TelegramBot\Telegram
     */
    public function setCustomUpdate($update)
    {
        $this->update = $update;
        return $this;
    }

    /**
     * Get custom update string for debug purposes
     *
     * @return string $update in json
     */
    public function getCustomUpdate()
    {
        return $this->update;
    }

    /**
     * Handle getUpdates method
     *
     * @return \Longman\TelegramBot\Telegram
     */

    public function handleGetUpdates($limit = null, $timeout = null)
    {
        //DB Query
        $last_message = DB::selectMessages(1);

        if (isset($last_message[0]['update_id'])) {
            //As explained in the telegram bot api documentation
            $offset = $last_message[0]['update_id']+1;
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
            print(date('Y-m-d H:i:s', time()).' - Processed '.$a." updates\n");
        } else {
            print(date('Y-m-d H:i:s', time())." - Fail fetch updates\n");
            echo $ServerResponse->printError()."\n";
        }

        //return $results
    }

    /**
     * Handle bot request from wekhook
     *
     * @return \Longman\TelegramBot\Telegram
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

        $update = new Update($post, $this->bot_name);
        return $this->processUpdate($update);
    }

    /**
     * Process Handle bot request
     *
     * @return \Longman\TelegramBot\Telegram
     */

    public function processUpdate(update $update)
    {
        //Load admin Commands
        if ($this->admin_enabled) {
            $message = $update->getMessage();

            //Admin command avaiable in any chats
            //$from = $message->getFrom();
            //$user_id = $from->getId();

            //Admin command avaiable only in single chat with the bot
            $chat = $message->getChat();
            $user_id = $chat->getId();

            if (in_array($user_id, $this->admins_list)) {
                $this->addCommandsPath(BASE_PATH.'/Admin');
            }
        }

        DB::insertRequest($update);

        // check type
        $message = $update->getMessage();
        $type = $message->getType();

        switch ($type) {
            default:
            case 'text':
                return $this->executeCommand('Genericmessage', $update);
                break;
            case 'command':
                // execute command
                $command = $message->getCommand();
                return $this->executeCommand($command, $update);
                break;
            case 'new_chat_participant':
                // trigger new participant
                return $this->executeCommand('Newchatparticipant', $update);
                break;
            case 'left_chat_participant':
                // trigger left chat participant
                return $this->executeCommand('Leftchatparticipant', $update);
                break;
            case 'new_chat_title':
                // trigger new_chat_title
                return $this->executeCommand('Newchattitle', $update);
                break;
            case 'delete_chat_photo':
                // trigger delete_chat_photo
                return $this->executeCommand('Deletechatphoto', $update);
                break;
            case 'group_chat_created':
                // trigger group_chat_created
                return $this->executeCommand('Groupchatcreated', $update);
                break;
        }
    }

    /**
     * Execute /command
     *
     * @return mixed
     */
    public function executeCommand($command, Update $update)
    {
        $class = $this->getCommandClass($command, $update);

        if (!$class) {
            //handle a generic command or non existing one
            return $this->executeCommand('Generic', $update);
        }

        if (!$class->isEnabled()) {
            return false;
        }

        //execute() methods will be execute after preexecute() methods
        //this for prevent to execute db query without connection
        return $class->preExecute();
    }

    /**
     * Get command class
     *
     * @return object
     */
    public function getCommandClass($command, Update $update = null)
    {
        $this->commands_dir = array_unique($this->commands_dir);
        $this->commands_dir = array_reverse($this->commands_dir);

        $command = $this->sanitizeCommand($command);
        $class_name = ucfirst($command) . 'Command';
        $class_name_space = __NAMESPACE__ . '\\Commands\\' . $class_name;

        foreach ($this->commands_dir as $dir) {
            if (is_file($dir . '/' . $class_name . '.php')) {
                require_once($dir . '/' . $class_name . '.php');
                if (!class_exists($class_name_space)) {
                    continue;
                }
                $class = new $class_name_space($this);

                if (!empty($update)) {
                    $class->setUpdate($update);
                }

                return $class;
            }
        }

        if (class_exists($class_name_space)) {
            $class = new $class_name_space($this);
            if (!empty($update)) {
                $class->setUpdate($update);
            }

            if (is_object($class)) {
                return $class;
            }
        }

        return false;
    }


    protected function sanitizeCommand($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        return $str;
    }

    /**
     * Enable Admin Account
     *
     * @param array list of admins
     *
     * @return string
     */
    public function enableAdmins(array $admins_list)
    {
        foreach ($admins_list as $admin) {
            if ($admin > 0) {
                $this->admins_list[] = $admin;
            } else {
                throw new TelegramException('Invalid value "'.$admin.'" for admin!');
            }
        }

        $this->admin_enabled = true;

        return $this;
    }

    /**
     * check id user require the db connection
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
     * @return object
     */
    public function addCommandsPath($folder)
    {
        if (!is_dir($folder)) {
            throw new TelegramException('Commands folder not exists!');
        }
        $this->commands_dir[] = $folder;
        return $this;
    }


    /**
     * Set custom upload path
     *
     * @return object
     */
    public function setUploadPath($folder)
    {
        $this->upload_path = $folder;
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
     * Set custom Download path
     *
     * @return object
     */
    public function setDownloadPath($folder)
    {
        $this->download_path = $folder;
        return $this;
    }

    /**
     * Get custom Download path
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
     * @return object
     */
    public function setCommandConfig($command, array $array)
    {
        $this->commands_config[$command] = $array;
        return $this;
    }

    /**
     * Get command config
     *
     * @return object
     */
    public function getCommandConfig($command)
    {
        return isset($this->commands_config[$command]) ? $this->commands_config[$command] : array();
    }


    /**
     * Get API KEY
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Get BOT NAME
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
     * @return string
     */
    public function setWebHook($url, $path_certificate = null)
    {
        if (empty($url)) {
            throw new TelegramException('Hook url is empty!');
        }

        $result = Request::setWebhook($url, $path_certificate);

        if (!$result->isOk()) {
            throw new TelegramException(
                'Webhook was not set! Error: '.$result->getErrorCode().' '. $result->getDescription()
            );
        }

        return $result;
    }

    /**
     * Unset Webhook for bot
     *
     * @return string
     */
    public function unsetWebHook()
    {
        $result = Request::setWebhook();

        if (!$result->isOk()) {
            throw new TelegramException(
                'Webhook was not unset! Error: '.$result->getErrorCode().' '. $result->getDescription()
            );
        }

        return $result;
    }

    /**
     * Get available message types
     *
     * @return array
     */
    public function getMessageTypes()
    {
        return $this->message_types;
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
}
