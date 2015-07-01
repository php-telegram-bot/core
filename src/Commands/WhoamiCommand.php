<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * writteno by Marco Boretto <marco.bore@gmail.com>
 */

namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class WhoamiCommand extends Command
{

	public function execute() {
		$update = $this->getUpdate();
		$message = $this->getMessage();



		$chat_id = $message->getChat()->getId();
		$text = $message->getText(true);

		$from = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName().' '.$message->getFrom()->getUsername();

  		$data = array();
  		$data['chat_id'] = $chat_id;
  		$data['text'] = 'Your name is: ' . $from;


		$result = Request::sendMessage($data);

	}


}

