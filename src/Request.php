<?php
/*
 * This file is part of the TelegramApi package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramApi;


class Request
{
	private static $api_key = '';

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



	public static function setApiKey($api_key) {
		self::$api_key = $api_key;
		if (empty(self::$api_key)) {
			throw new Exception('API KEY not defined!');
		}
	}


	public static function getInput() {
		$input = file_get_contents('php://input');
		return $input;
	}


	public static function send($action, array $data = null) {
		$ch = curl_init();
		$curlConfig = array(
		    CURLOPT_URL					=> 'https://api.telegram.org/bot'.self::$api_key.'/'.$action,
		    CURLOPT_POST 				=> true,
		    CURLOPT_RETURNTRANSFER	=> true,
		    //CURLOPT_HTTPHEADER 		=> array('Content-Type: text/plain'),
		    //CURLOPT_POSTFIELDS			=> $data
		);

		if (!empty($data)) {
			$curlConfig[CURLOPT_POSTFIELDS] = $data;
		}


		curl_setopt_array($ch, $curlConfig);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}



	public static function sendMessage(array $data) {

		if (empty($data)) {
			throw new Exception('Data is empty!');
		}

		$result = self::send('sendMessage', $data);
		return $result;
	}


}
