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


class User
{

	protected $id;
	protected $first_name;
	protected $last_name;
	protected $username;




	public function __construct(array $data) {

		$this->id = isset($data['id']) ? $data['id'] : null;
		if (empty($this->id)) {
			throw new \Exception('id is empty!');
		}

		$this->first_name = isset($data['first_name']) ? $data['first_name'] : null;
		if (empty($this->first_name)) {
			throw new \Exception('first_name is empty!');
		}

		$this->last_name = isset($data['last_name']) ? $data['last_name'] : null;
		$this->username = isset($data['username']) ? $data['username'] : null;

	}


	public function getId() {

		return $this->id;
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
