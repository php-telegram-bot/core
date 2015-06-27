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

use Longman\TelegramApi\Entities\Update;

abstract class Command
{
	protected $update;
	protected $message;
	protected $command;

	public function __construct(Update $update) {
		$this->update = $update;
		$this->message = $this->update->getMessage();

	}


	public abstract function execute();


	public function getUpdate() {
		return $this->update;
	}

	public function getMessage() {
		return $this->message;
	}

	public function setCommand($command) {
		$this->command = $command;
		return $this;
	}



}
