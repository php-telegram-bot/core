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


define('VERSION', '0.0.1');


use Longman\TelegramBot\Entities\Update;

/**
 * @package 		Telegram
 * @author 		Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright 		Avtandil Kikabidze <akalongman@gmail.com>
 * @license 		http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link 			http://www.github.com/akalongman/telegram-api
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
			throw new Exception('Input is empty!');
		}



		$post = json_decode($this->input, true);
		if (empty($post)) {
			throw new Exception('Invalid JSON!');
		}


		$update = new Update($post);
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
	protected function getCommandClass($command, Update $update) {
		$this->commands_dir = array_unique($this->commands_dir);
		$this->commands_dir = array_reverse($this->commands_dir);
		$class_name = ucfirst($command).'Command';

		foreach($this->commands_dir as $dir) {
			if (is_file($dir.'/'.$class_name.'.php')) {
				require_once($dir.'/'.$class_name.'.php');
				$class = new $class_name($update);
				return $class;
			}
		}

		$class_name = __NAMESPACE__ . '\\Commands\\' . $class_name;
		$class = new $class_name($update);
		if (is_object($class)) {
			return $class;
		}

		return false;
	}

	/**
	* Add custom commands path
	*
	* @return object
	*/
	public function addCommandsPath($folder) {
		if (!is_dir($folder)) {
			throw new Exception('Commands folder not exists!');
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



}
