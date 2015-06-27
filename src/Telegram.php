<?php
/*
 * This file is part of the TelegramApi package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramApi;


ini_set('max_execution_time', 0);
ini_set('memory_limit', -1);


use Longman\TelegramApi\Entities\Update;

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
	* @var \Longman\TelegramApi\Entities\Update
	*/
	protected $update;

	/**
	* Constructor
	*
	* @param string $api_key
	*/
	public function __construct($api_key) {
		$this->api_key = $api_key;
		Request::setApiKey($this->api_key);
	}


	/**
	* Set custom update string for debug purposes
	*
	* @param string $update
	*
	* @return \Longman\TelegramApi\Telegram
	*/
	public function setCustomUpdate($update) {
		$this->update = $update;
		return $this;
	}

	/**
	* Handle bot request
	*
	* @return \Longman\TelegramApi\Telegram
	*/
	public function handle() {


		$this->input = Request::getInput();
		if (!empty($this->update)) {
			$this->input = $this->update;
		}


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

}
