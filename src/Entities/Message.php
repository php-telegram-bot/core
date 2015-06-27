<?php
/*
 * This file is part of the TelegramApi package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramApi\Entities;




class Message
{
	protected $message_id;

	protected $from;

	protected $date;

	protected $chat;

	protected $forward_from;

	protected $forward_date;

	protected $reply_to_message;

	protected $text;

	protected $audio;

	protected $document;

	protected $photo;

	protected $sticker;

	protected $video;

	protected $contact;

	protected $location;

	protected $new_chat_participant;

	protected $left_chat_participant;

	protected $new_chat_title;

	protected $new_chat_photo;

	protected $delete_chat_photo;

	protected $group_chat_created;


	protected $_command;


	public function __construct(array $data) {

		$this->message_id = isset($data['message_id']) ? $data['message_id'] : null;
		if (empty($this->message_id)) {
			throw new Exception('message_id is empty!');
		}

		$this->from = isset($data['from']) ? $data['from'] : null;
		if (empty($this->from)) {
			throw new Exception('from is empty!');
		}
		$this->from = new User($this->from);


		$this->date = isset($data['date']) ? $data['date'] : null;
		if (empty($this->date)) {
			throw new Exception('date is empty!');
		}

		$this->chat = isset($data['chat']) ? $data['chat'] : null;
		if (empty($this->chat)) {
			throw new Exception('chat is empty!');
		}
		$this->chat = new Chat($this->chat);


		$this->text = isset($data['text']) ? $data['text'] : null;

	}



	public function getCommand() {
		if (!empty($this->_command)) {
			return $this->_command;
		}


		$cmd = strtok($this->text,  ' ');

		if (substr($cmd, 0, 1) === '/') {
			$cmd = substr($cmd, 1);
			return $this->_command = $cmd;
		}
		return false;
	}




	public function getMessageId() {

		return $this->message_id;
	}

	public function getDate() {

		return $this->date;
	}

	public function getFrom() {

		return $this->from;
	}


	public function getChat() {

		return $this->chat;
	}

	public function getText($without_cmd = false) {
		$text = $this->text;
		if ($without_cmd) {
			$command = $this->getCommand();
			$text = substr($text, strlen('/'.$command.' '), strlen($text));
		}

		return $text;
	}



}
