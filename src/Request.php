<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot;


class Request
{
	private static $telegram;
	private static $input;

	private static $methods = array(
		'getMe',
		'sendMessage',
		'forwardMessage',
		'sendPhoto',
		'sendAudio',
		'sendDocument',
		'sendSticker',
		'sendVideo',
		'sendLocation',
		'sendChatAction',
		'getUserProfilePhotos',
		'getUpdates',
		'setWebhook',
	);



	public static function initialize(Telegram $telegram) {
		self::$telegram = $telegram;
	}


	public static function getInput() {
		if ($update = self::$telegram->getCustomUpdate()) {
			self::$input = $update;
		} else {
			self::$input = file_get_contents('php://input');
		}
		self::log();
		return self::$input;
	}


	private static function log() {
		if (!self::$telegram->getLogRequests()) {
			return false;
		}
		$path = self::$telegram->getLogPath();
		if (!$path) {
			return false;
		}

		$status = file_put_contents($path, self::$input."\n", FILE_APPEND);

		return $status;
	}



	public static function send($action, array $data = null) {
		$ch = curl_init();
		$curlConfig = array(
		    CURLOPT_URL					=> 'https://api.telegram.org/bot'.self::$telegram->getApiKey().'/'.$action,
		    CURLOPT_POST 				=> true,
		    CURLOPT_RETURNTRANSFER	=> true,
		    //CURLOPT_HTTPHEADER 		=> array('Content-Type: application/x-www-form-urlencoded'),
		    //CURLOPT_HTTPHEADER 		=> array('Content-Type: text/plain'),
		    //CURLOPT_POSTFIELDS			=> $data
		);

		if (!empty($data)) {
			if (substr($data['text'], 0, 1) === '@') {
				$data['text'] = ' '.$data['text'];
			}

			//$data = array_map('urlencode', $data);
			$curlConfig[CURLOPT_POSTFIELDS] = $data;
		}


		curl_setopt_array($ch, $curlConfig);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}



	public static function sendMessage(array $data) {

		if (empty($data)) {
			throw new \Exception('Data is empty!');
		}

		$result = self::send('sendMessage', $data);
		return $result;
	}


}
