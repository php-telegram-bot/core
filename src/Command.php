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

use Longman\TelegramBot\Entities\Update;

abstract class Command
{
	protected $telegram;
	protected $update;
	protected $message;
	protected $command;

	protected $usage = 'Command help text';
	protected $version = '1.0.0';

	public function __construct(Telegram $telegram) {
		$this->telegram = $telegram;
	}

	public function setUpdate(Update $update) {
		$this->update = $update;
		$this->message = $this->update->getMessage();
		return $this;
	}


	public abstract function execute();


	public function getUpdate() {
		return $this->update;
	}

	public function getMessage() {
		return $this->message;
	}


	public function getTelegram() {
		return $this->telegram;
	}

	public function setCommand($command) {
		$this->command = $command;
		return $this;
	}

	public function getUsage() {
		return $this->usage;
	}

	public function getVersion() {
		return $this->version;
	}

	public function getHelp() {
		return $this->getUsage()."\n".$this->getVersion();
	}

}
