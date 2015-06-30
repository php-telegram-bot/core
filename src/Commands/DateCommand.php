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

class DateCommand extends Command
{
	private $google_api_key = '';
	private $base_url = 'https://maps.googleapis.com/maps/api';
	private $date_format = 'd-m-Y H:i:s';






	private function getCoordinates($location) {
		$url = $this->base_url.'/geocode/json?';
		$params = 'address='.urlencode($location);
		if (!empty($this->google_api_key)) {
			$params .= '&key='.$this->google_api_key;
		}

		$data = $this->request($url.$params);
  		if (empty($data)) {
  			return false;
  		}

  		$data = json_decode($data, true);
    		if (empty($data)) {
  			return false;
  		}

  		if ($data['status'] !== 'OK') {
  			return false;
  		}

  		$lat = $data['results'][0]['geometry']['location']['lat'];
  		$lng = $data['results'][0]['geometry']['location']['lng'];
  		$acc = $data['results'][0]['geometry']['location_type'];
  		$types = $data['results'][0]['types'];

  		return array($lat, $lng, $acc, $types);
	}



	private function getDate($lat, $lng) {
		$url = $this->base_url.'/timezone/json?';


		$timestamp = time();


		$params = 'location='.urlencode($lat).','.urlencode($lng).'&timestamp='.urlencode($timestamp);
		if (!empty($this->google_api_key)) {
			$params .= '&key='.$this->google_api_key;
		}

		$data = $this->request($url.$params);
  		if (empty($data)) {
  			return false;
  		}

  		$data = json_decode($data, true);
    		if (empty($data)) {
  			return false;
  		}

  		if ($data['status'] !== 'OK') {
  			return false;
  		}


  		$local_time = $timestamp + $data['rawOffset'] + $data['dstOffset'];

  		return array($local_time, $data['timeZoneId']);
	}

	private function getFormattedDate($location) {
		if (empty($location)) {
			return 'The time in nowhere is never';
		}
		list($lat, $lng, $acc, $types) = $this->getCoordinates($location);

		if (empty($lat) || empty($lng)) {
			return 'It seems that in "'.$location.'" they do not have a concept of time.';
		}

		list($local_time, $timezone_id) = $this->getDate($lat, $lng);



		$date_utc = new \DateTime(date('Y-m-d H:i:s', $local_time), new \DateTimeZone($timezone_id));
		//$timestamp = $date_utc->format($this->date_format);

		$return = 'The local time in '.$timezone_id.' is: '.date($this->date_format, $local_time).'';

		return $return;
	}


	private function request($url) {
		$ch = curl_init();
		$curlConfig = array(
		    CURLOPT_URL					=> $url,
		    CURLOPT_RETURNTRANSFER	=> true,
		);

		curl_setopt_array($ch, $curlConfig);
		$response = curl_exec($ch);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code !== 200) {
			throw new Exception('Error receiving data from url');
		}
		curl_close($ch);

		return $response;
	}


	public function execute() {
		$update = $this->getUpdate();
		$message = $this->getMessage();



		$chat_id = $message->getChat()->getId();
		$message_id = $message->getMessageId();
		$text = $message->getText(true);



		if (empty($text)) {
			$text = 'You must specify location in format: /date <city>';
		} else {
			$date = $this->getformattedDate($text);
	  		if (empty($date)) {
	  			$text = 'Can not find date for location: '.$text;
	  		} else {
	  			$text = $date;
	  		}
		}


  		$data = array();
  		$data['chat_id'] = $chat_id;
  		$data['reply_to_message_id'] = $message_id;
  		$data['text'] = $text;


		$result = Request::sendMessage($data);
	}


}
