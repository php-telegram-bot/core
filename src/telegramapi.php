<?php
namespace TelegramApi;

class TelegramApi
{
	private $api_key = '';


	public function __construct($api_key) {
		$this->api_key = $api_key;
	}



	public function execute() {
		$input = file_get_contents('php://input');
		if (empty($input)) {
			throw new Exception('Input is empty!');
		}
		$post = json_decode($input, true);
		if (empty($post)) {
			throw new Exception('Invalid JSON!');
		}

		  print_r($post);
		  die;


		$message = new Message($post);



		$cmd = $this->getCommand($post);
  var_dump($cmd);
  die;




	}

	public function getCommand($post) {
		$cmd = strtok($post,  ' ');

		if (substr($cmd, 0, 1) === '/') {
			$cmd = substr($cmd, 1);
		}
		return $cmd;
	}



	public function request($action, $data) {


		$ch = curl_init();
		$curlConfig = array(
		    CURLOPT_URL					=> 'https://api.telegram.org/bot'.$this->api_key.'/'.$action,
		    CURLOPT_POST 				=> true,
		    CURLOPT_RETURNTRANSFER	=> true,
		    //CURLOPT_HTTPHEADER 		=> array('Content-Type: text/plain'),
		    CURLOPT_POSTFIELDS			=> $data
		);
		curl_setopt_array($ch, $curlConfig);
		$result = curl_exec($ch);
		curl_close($ch);



	}



}
