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
    protected $version = '0.0.16';

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
     * MySQL Integration
     *
     * @var boolean
     */
    protected $mysql_enabled;

    /**
     * MySQL credentials
     *
     * @var array
     */
    protected $mysql_credentials = array();

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

        Request::initialize($this);
    }

    /**
     * Set custom update string for debug purposes
     *
     * @param string $update
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
     * @return string $update
     */
    public function getCustomUpdate()
    {
        return $this->update;
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
     * Handle bot request
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

        //Load admin Commands
        if ($this->admin_enabled) {
            $message = $update->getMessage();

            $from = $message->getFrom();
            $user_id = $from->getId();

            //Admin command avaiable only in single chat with the bot
            //$chat = $message->getChat();
            //$user_id = $chat->getId();

            if (in_array($user_id, $this->admins_list)) {
                $this->addCommandsPath(BASE_PATH.'/Admin');
            }
        }

        $this->insertRequest($update);

        $message = $update->getMessage();

        // check type
        $type = $message->getType();

        switch ($type) {
            default:
            case 'text':
                // do nothing
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

        //print_r($class);
        if (!$class) {
            //handle a generic command or non existing one
            return $this->executeCommand('Generic', $update);
        }

        if (!$class->isEnabled()) {
            return false;
        }


        return $class->execute();
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
        //$str[0] = strtolower($str[0]);
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
                throw new TelegramException('Only users can be admin not chat!');
            }
        }

        $this->admin_enabled = true;

        return $this;
    }

    /**
     *DB Mehods
     *
     *
     */

    /**
     * Enable MySQL integration
     *
     * @param array $credentials MySQL credentials
     *
     * @return string
     */
    public function enableMySQL(array $credentials, $table_prefix = null)
    {
        if (empty($credentials)) {
            throw new TelegramException('MySQL credentials not provided!');
        }
        $this->mysql_credentials = $credentials;

        $dsn = 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'];
        $options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
        try {
            $pdo = new \PDO($dsn, $credentials['user'], $credentials['password'], $options);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            //Define table
            define('TB_MESSAGES', $table_prefix.'messages');
            define('TB_USERS', $table_prefix.'users');
            define('TB_CHATS', $table_prefix.'chats');
            define('TB_USERS_CHATS', $table_prefix.'users_chats');
        } catch (\PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        $this->pdo = $pdo;
        $this->mysql_enabled = true;

        return $this;
    }

    /**
     * Convert from unix timestamp to timestamp
     *
     * @return string
     */
    protected function toTimestamp($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    /**
     * Insert request in db
     *
     * @return bool
     */
 //   protected function insertRequest(Update $update)

    public function insertRequest(Update $update)
    {
        if (empty($this->pdo)) {
            return false;
        }

        $message = $update->getMessage();

        try {
            //Users table
            $sth1 = $this->pdo->prepare('INSERT INTO `'.TB_USERS.'`
                (
                `id`, `username`, `first_name`, `last_name`, `created_at`, `updated_at`
                )
                VALUES (
                :id, :username, :first_name, :last_name, :date, :date
                )
                ON DUPLICATE KEY UPDATE `username`=:username, `first_name`=:first_name, 
                `last_name`=:last_name, `updated_at`=:date
               ');

            $from = $message->getFrom();
            $date = $this->toTimestamp($message->getDate());
            $user_id = $from->getId();
            $username = $from->getUsername();
            $first_name = $from->getFirstName();
            $last_name = $from->getLastName();

            $sth1->bindParam(':id', $user_id, \PDO::PARAM_INT);
            $sth1->bindParam(':username', $username, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':first_name', $first_name, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':last_name', $last_name, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':date', $date, \PDO::PARAM_STR);

            $status = $sth1->execute();

            //chats table
            $sth2 = $this->pdo->prepare('INSERT INTO `'.TB_CHATS.'`
                (
                `id`, `title`, `created_at` ,`updated_at`
                )
                VALUES (:id, :title, :date, :date)
                ON DUPLICATE KEY UPDATE `title`=:title, `updated_at`=:date
                ');

            $chat_id = $message->getChat()->getId();
            $chat_title = $message->getChat()->getTitle();

            $sth2->bindParam(':id', $chat_id, \PDO::PARAM_INT);
            $sth2->bindParam(':title', $chat_title, \PDO::PARAM_STR, 255);
            $sth2->bindParam(':date', $date, \PDO::PARAM_STR);

            $status = $sth2->execute();

            //user_chat table
            $sth3 = $this->pdo->prepare('INSERT IGNORE INTO `'.TB_USERS_CHATS.'`
                (
                `user_id`, `chat_id`
                )
                VALUES (:user_id, :chat_id)
                ');

            $sth3->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $sth3->bindParam(':chat_id', $chat_id, \PDO::PARAM_INT);

            $status = $sth3->execute();

            //Messages Table
            $sth = $this->pdo->prepare('INSERT IGNORE INTO `'.TB_MESSAGES.'`
                (
                `update_id`, `message_id`, `user_id`, `date`, `chat_id`, `forward_from`,
                `forward_date`, `reply_to_message`, `text`
                )
                VALUES (:update_id, :message_id, :user_id, :date, :chat_id, :forward_from,
                :forward_date, :reply_to_message, :text)');

            $update_id = $update->getUpdateId();
            $message_id = $message->getMessageId();
            $forward_from = $message->getForwardFrom();
            $forward_date = $this->toTimestamp($message->getForwardDate());
            $reply_to_message = $message->getReplyToMessage();
            if (is_object($reply_to_message)) {
                $reply_to_message = $reply_to_message->toJSON();
            }
            $text = $message->getText();

    
            $sth->bindParam(':update_id', $update_id, \PDO::PARAM_INT);
            $sth->bindParam(':message_id', $message_id, \PDO::PARAM_INT);
            $sth->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $sth->bindParam(':date', $date, \PDO::PARAM_STR);
            $sth->bindParam(':chat_id', $chat_id, \PDO::PARAM_STR);
            $sth->bindParam(':forward_from', $forward_from, \PDO::PARAM_STR);
            $sth->bindParam(':forward_date', $forward_date, \PDO::PARAM_STR);
            $sth->bindParam(':reply_to_message', $reply_to_message, \PDO::PARAM_STR);
            $sth->bindParam(':text', $text, \PDO::PARAM_STR);

            $status = $sth->execute();

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        return true;
    }


    /**
     * Send Message in all the active chat
     *
     * @param date string yyyy-mm-dd hh:mm:ss
     *
     * @return bool
     */

    public function sendToActiveChats(
        $callback_function,
        array $data,
        $send_chats = true,
        $send_users = true,
        $date_from = null,
        $date_to = null
    ) {
        if (empty($this->pdo)) {
            return false;
        }

        $callback_path = __NAMESPACE__ .'\Request';
        if (! method_exists($callback_path, $callback_function)) {
            throw new TelegramException('Methods: '.$callback_function.' not found in class Request.');
        }

        if (!$send_chats & !$send_users) {
            return false;
        }


        try {
            $query = 'SELECT * ,  
                '.TB_CHATS.'.`id` AS `chat_id`,
                '.TB_CHATS.'.`updated_at` AS `chat_updated_at`
                FROM `'.TB_CHATS.'` LEFT JOIN `'.TB_USERS.'`
                ON '.TB_CHATS.'.`id`='.TB_USERS.'.`id`';

            //Building parts of query
            $chat_or_user = '';
            $where = [];
            $tokens = [];
            if ($send_chats & !$send_users) {
                $where[] = TB_CHATS.'.`id` < 0';
            } elseif (!$send_chats & $send_users) {
                $where[] = TB_CHATS.'.`id` > 0';
            }

            if (! is_null($date_from)) {
                $where[] = TB_CHATS.'.`updated_at` >= :date_from';
                $tokens[':date_from'] =  $date_from;
            }

            if (! is_null($date_to)) {
                $where[] = TB_CHATS.'.`updated_at` <= :date_to';
                $tokens[':date_to'] = $date_to;
            }

            $a=0;
            foreach ($where as $part) {
                if ($a) {
                    $query .= ' AND '.$part;
                } else {
                    $query .= ' WHERE '.$part;
                    ++$a;
                }
            }
            //echo $query."\n";

            $sth = $this->pdo->prepare($query);
            $sth->execute($tokens);

            $results = [];
            while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
                //print_r($row);
                $data['chat_id'] = $row['chat_id'];
                $results[] = call_user_func_array($callback_path.'::'.$callback_function, array( $data));
            }

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        return $results;
    }

    /**
     *End DB Mehods
     *
     */



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
    public function setWebHook($url)
    {
        if (empty($url)) {
            throw new TelegramException('Hook url is empty!');
        }
        $result = Request::setWebhook($url);

        if (!$result['ok']) {
            throw new TelegramException('Webhook was not set! Error: '.$result[error_code].' '. $result['description']);
        }

        return $result['description'];
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
