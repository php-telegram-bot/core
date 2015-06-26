<?php


class EchoCommand extends Commands
{



	public function execute() {
		$args = func_get_args();




	}


}





$db_user = 'dbuser';
$db_pass = '8CihEPdp';

$API_KEY = '115402299:AAH-5SiSZKGiZinOgA8TfyCkO9ml9GmIFXc';
$action = 'sendMessage';
$post = array(
		'chat_id'=>0,
		'text'=>'',
	);


$post = file_get_contents('php://input');
$status = file_put_contents('data.log', $post."\n\n", FILE_APPEND);




if (!empty($_GET['cmd'])) {
	$post = $_GET['cmd'];
}

$post = json_decode($post, true);




$chat_id = $post['message']['chat']['id'];
if (empty($chat_id)) {
	return false;
}


$text = $post['message']['text'];
if (empty($text)) {
	return false;
}

$cmd = strtok($text,  ' ');

switch ($cmd) {
	case '/echo';
		$text = str_replace($cmd, '', $text);


		$post['chat_id'] = $chat_id;
		$post['text'] = $text;


		request($post, 'sendMessage');
		break;

	case '/help';
		$text = 'GeoBot v1.0.0';
		$text = 'Commands:
		/echo - Echo message

		';

		$post['chat_id'] = $chat_id;
		$post['text'] = $text;


		request($post, 'sendMessage');
		break;


}

function request($post, $action) {
	global $API_KEY;

	$ch = curl_init();
	$curlConfig = array(
	    CURLOPT_URL					=> 'https://api.telegram.org/bot'.$API_KEY.'/'.$action,
	    CURLOPT_POST 				=> true,
	    CURLOPT_RETURNTRANSFER	=> true,
	    CURLOPT_POSTFIELDS			=> $post
	);
	curl_setopt_array($ch, $curlConfig);
	$result = curl_exec($ch);
	curl_close($ch);
}


/*$data = '';
$data .= 'POST: '.print_r(json_decode($post), true)."\n";
$data .= "= = = =\n\n";
$status = file_put_contents('data.log', $data, FILE_APPEND);

*/




