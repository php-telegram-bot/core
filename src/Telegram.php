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


ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);

date_default_timezone_set('UTC');


define('VERSION', '0.0.2');


use Longman\TelegramBot\Entities\Update;

/**
 * @package 		Telegram
 * @author 		Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright 		Avtandil Kikabidze <akalongman@gmail.com>
 * @license 		http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link 			http://www.github.com/akalongman/telegram-bot
 */
class Telegram
{
	/**
	* Telegram API key
	*
	* @var string
	*/
	protected $api_key = '';

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
	* Constructor
	*
	* @param string $api_key
	*/
	public function __construct($api_key) {
		$this->api_key = $api_key;
		Request::initialize($this);
	}


	/**
	* Set custom update string for debug purposes
	*
	* @param string $update
	*
	* @return \Longman\TelegramBot\Telegram
	*/
	public function setCustomUpdate($update) {
		$this->update = $update;
		return $this;
	}

	/**
	* Get custom update string for debug purposes
	*
	* @return string $update
	*/
	public function getCustomUpdate() {
		return $this->update;
	}


	/**
	* Get commands list
	*
	* @return string $update
	*/
	public function getCommandsList() {
		// iterate files
		// getCommandClass







		return $this->update;
	}





	/**
	* Set log requests
	*
	* @param bool $log_requests
	*
	* @return \Longman\TelegramBot\Telegram
	*/
	public function setLogRequests($log_requests) {
		$this->log_requests = $log_requests;
		return $this;
	}

	/**
	* Get log requests
	*
	* @return bool
	*/
	public function getLogRequests() {
		return $this->log_requests;
	}



	/**
	* Set log path
	*
	* @param string $log_path
	*
	* @return \Longman\TelegramBot\Telegram
	*/
	public function setLogPath($log_path) {
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
	public function getLogPath() {
		return $this->log_path;
	}

	/**
	* Handle bot request
	*
	* @return \Longman\TelegramBot\Telegram
	*/
	public function handle() {


		$this->input = Request::getInput();


		if (empty($this->input)) {
			throw new \Exception('Input is empty!');
		}



		$post = json_decode($this->input, true);
		if (empty($post)) {
			throw new \Exception('Invalid JSON!');
		}


		$update = new Update($post);

		$this->insertRequest($update);

		$command = $update->getMessage()->getCommand();
		if (!empty($command)) {
			return $this->executeCommand($command, $update);
		}



	}

	/**
	* Execute /command
	*
	* @return mixed
	*/
	protected function executeCommand($command, Update $update) {
		$class = $this->getCommandClass($command, $update);
		if (empty($class)) {
			return false;
		}

		return $class->execute();
	}

	/**
	* Get command class
	*
	* @return object
	*/
	protected function getCommandClass($command, Update $update = null) {
		$this->commands_dir = array_unique($this->commands_dir);
		$this->commands_dir = array_reverse($this->commands_dir);
		$class_name = ucfirst($command).'Command';

		foreach($this->commands_dir as $dir) {
			if (is_file($dir.'/'.$class_name.'.php')) {
				require_once($dir.'/'.$class_name.'.php');
				$class = new $class_name($this);
				if (!empty($update)) {
					$class->setUpdate($update);
				}

				return $class;
			}
		}

		$class_name = __NAMESPACE__ . '\\Commands\\' . $class_name;
		$class = new $class_name($this);
		if (!empty($update)) {
			$class->setUpdate($update);
		}

		if (is_object($class)) {
			return $class;
		}

		return false;
	}


	/**
	* Insert request in db
	*
	* @return bool
	*/
	protected function insertRequest(Update $update) {
		if (empty($this->pdo)) {
			return false;
		}

		try {
			$sth = $this->pdo->prepare('INSERT INTO `messages`
				(
				`update_id`, `message_id`, `from`, `date`, `chat`, `forward_from`,
				`forward_date`, `reply_to_message`, `text`
				)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');

			$message = $update->getMessage();

			$update_id = $update->getUpdateId();
			$message_id = $message->getMessageId();
			$from = $message->getFrom()->toJSON();
			$date = $message->getDate();
			$chat = $message->getChat()->toJSON();
			$forward_from = $message->getForwardFrom();
			$forward_date = $message->getForwardDate();
			$reply_to_message = $message->getReplyToMessage();
			if (is_object($reply_to_message)) {
				$reply_to_message = $reply_to_message->toJSON();
			}
			$text = $message->getText();


			$sth->bindParam(1, $update_id, \PDO::PARAM_INT);
			$sth->bindParam(2, $message_id, \PDO::PARAM_INT);
			$sth->bindParam(3, $from, \PDO::PARAM_STR, 255);
			$sth->bindParam(4, $date, \PDO::PARAM_INT);
			$sth->bindParam(5, $chat, \PDO::PARAM_STR);
			$sth->bindParam(6, $forward_from, \PDO::PARAM_STR);
			$sth->bindParam(7, $forward_date, \PDO::PARAM_INT);
			$sth->bindParam(8, $reply_to_message, \PDO::PARAM_STR);
			$sth->bindParam(9, $text, \PDO::PARAM_STR);

			$status = $sth->execute();





			/*$status = $executeQuery->execute(
				array(
					$update_id, $message_id, $from, $date, $chat, $forward_from,
					$forward_date, $reply_to_message, $text,
					)

				);*/



		}
		catch(PDOException $e) {
			throw new \Exception($e->getMessage());
		}


		return true;
	}


	/**
	* Add custom commands path
	*
	* @return object
	*/
	public function addCommandsPath($folder) {
		if (!is_dir($folder)) {
			throw new \Exception('Commands folder not exists!');
		}
		$this->commands_dir[] = $folder;
	}

	/**
	* Get API KEY
	*
	* @return string
	*/
	public function getApiKey() {
		return $this->api_key;
	}

	/**
	* Set Webhook for bot
	*
	* @return string
	*/
	public function setWebHook($url){
		return Request::send('setWebhook', array('url'=>$url));
	}


	/**
	* Enable MySQL integration
	*
	* @param array $credentials MySQL credentials
	*
	* @return string
	*/
	public function enableMySQL(array $credentials){
		if (empty($credentials)) {
			throw new \Exception('MySQL credentials not provided!');
		}
		$this->mysql_credentials = $credentials;

		$dsn = 'mysql:host='.$credentials['host'].';dbname='.$credentials['database'];
		$options = array(
			\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		);
		try {
			$pdo = new \PDO($dsn, $credentials['user'], $credentials['password'], $options);
			$pdo->setAttribute (\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
		}
		catch (\PDOException $e) {
			throw new \Exception($e->getMessage());
		}

		$this->pdo = $pdo;
		$this->mysql_enabled = true;

		return $this;
	}



}
