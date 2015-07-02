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




class Update extends Entity
{

	protected $update_id;
	protected $message;




	public function __construct(array $data) {

		$update_id = isset($data['update_id']) ? $data['update_id'] : null;

		$message = isset($data['message']) ? $data['message'] : null;

		if (empty($update_id)) {
			throw new \Exception('update_id is empty!');
		}

		$this->update_id = $update_id;
		$this->message = new Message($message);

	}

	public function getUpdateId() {

		return $this->update_id;
	}


	public function getMessage() {

		return $this->message;
	}



}
