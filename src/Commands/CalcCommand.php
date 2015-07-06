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
	protected $name = 'calc';
	protected $description = 'Calculate math expression';
	protected $usage = '/calc <expression>';
	protected $version = '1.0.0';
	protected $enabled = true;


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
		return $result;
	}


	protected function compute($expression) {

		// Load the compiler
		$compiler = \Hoa\Compiler\Llk::load(
		    new \Hoa\File\Read('hoa://Library/Math/Arithmetic.pp')
		);

		// Load the visitor, aka the "evaluator"
		$visitor = new \Hoa\Math\Visitor\Arithmetic();

		// Parse the expression
		$ast = $compiler->parse($expression);

		// Evaluate
		$result = $visitor->visit($ast);

		return $result;
	}


}

