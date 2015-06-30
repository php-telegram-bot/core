<?php
/*
 * This file is part of the TelegramApi package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramApi\Commands;

use Longman\TelegramApi\Request;
use Longman\TelegramApi\Command;
use Longman\TelegramApi\Entities\Update;

class HelpCommand extends Command
{

	public function execute() {
		$update = $this->getUpdate();
		$message = $this->getMessage();



		$chat_id = $message->getChat()->getId();
		$text = $message->getText(true);


  		$data = array();
  		$data['chat_id'] = $chat_id;
  		$data['text'] = 'GeoBot v. '.VERSION;


		$result = Request::sendMessage($data);

	}


}

