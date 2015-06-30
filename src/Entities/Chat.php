<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Entities;


class Chat
{

	protected $id;
	protected $title;
	protected $first_name;
	protected $last_name;
	protected $username;

	public function __construct(array $data) {

		$this->id = isset($data['id']) ? $data['id'] : null;
		if (empty($this->id)) {
			throw new \Exception('id is empty!');
		}

	}


	public function getId() {

		return $this->id;
	}


	public function getTitle() {

		return $this->title;
	}

	public function getFirstName() {

		return $this->first_name;
	}

	public function getLastName() {

		return $this->last_name;
	}


	public function getUsername() {

		return $this->username;
	}

}
