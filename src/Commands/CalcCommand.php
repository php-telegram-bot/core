<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto <marco.bore@gmail.com>
 */
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class CalcCommand extends Command
{

	public function execute() {
		$update = $this->getUpdate();
		$message = $this->getMessage();


		$chat_id = $message->getChat()->getId();
		$message_id = $message->getMessageId();
		$text = $message->getText(true);


  		$data = array();
  		$data['chat_id'] = $chat_id;
   		$data['reply_to_message_id'] = $message_id;
  		$data['text'] = $this->compute($text);


		$result = Request::sendMessage($data);

	}


	protected function compute($text) {
		$text = preg_replace('/[^0-9\+\-\*\/\(\) ]/i', '', trim($text));
		$compute = create_function('', 'return (' . trim($text) . ');' );
		$result = 0 + $compute();
		return $result;
	}


}

