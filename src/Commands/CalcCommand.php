<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
		$text = $message->getText(true);
		
		#$elm = explode(" ",$text);
		#array_shift($elm);
     
	#	$text = trim(implode(" ",$elm));
		$text = preg_replace('/[^0-9\+\-\*\/\(\) ]/i', '',trim($text));
		$compute = create_function('', 'return (' . trim($text) . ');' );


  		$data = array();
  		$data['chat_id'] = $chat_id;
  		$data['text'] = 0 + $compute();


		$result = Request::sendMessage($data);

	}


}

