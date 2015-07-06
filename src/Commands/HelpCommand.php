<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Command;
use Longman\TelegramBot\Entities\Update;

class HelpCommand extends Command
{
	protected $name = 'help';
	protected $description = 'Show bot commands help';
	protected $usage = '/help or /help <command>';
	protected $version = '1.0.0';
	protected $enabled = true;


	public function execute() {
		$update = $this->getUpdate();
		$message = $this->getMessage();



		$chat_id = $message->getChat()->getId();
		$message_id = $message->getMessageId();
		$text = $message->getText(true);

		$commands = $this->telegram->getCommandsList();
		if (empty($text)) {
			$msg = 'GeoBot v. '.$this->telegram->getVersion()."\n\n";
			$msg .= 'Commands List:'."\n";
			foreach($commands as $command) {
				if (!$command->isEnabled()) {
					continue;
				}
				$msg .= '/'.$command->getName().' - '.$command->getDescription()."\n";
			}

			$msg .= "\n".'For exact command help type: /help <command>';
		} else {
			$text = str_replace('/', '', $text);
			if (isset($commands[$text])) {
				$command = $commands[$text];
				$msg = 'Command: '.$command->getName().' v'.$command->getVersion()."\n";
				$msg .= 'Description: '.$command->getDescription()."\n";
				$msg .= 'Usage: '.$command->getUsage();
			} else {
				$msg = 'Command '.$text.' not found';
			}
		}


  		$data = array();
  		$data['chat_id'] = $chat_id;
  		$data['reply_to_message_id'] = $message_id;
  		$data['text'] = $msg;


		$result = Request::sendMessage($data);
		return $result;
	}


}

