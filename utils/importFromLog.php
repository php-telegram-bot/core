<?php
require __DIR__ . '/../vendor/autoload.php';

$filename='logfile.log';
$API_KEY = 'random'; 
$BOT_NAME = 'bot_name';
                                                                                                                                                                                                                     
define('PHPUNIT_TESTSUITE', 'some value');

$CREDENTIALS = array('host'=>'localhost', 'user'=>'', 'password'=>'', 'database'=>'');

$update = null;
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);
    $telegram->enableMySQL($CREDENTIALS);
    foreach (new SplFileObject($filename) as $current_line) {
        $json_decoded = json_decode($update, true);
        if (!is_null($json_decoded)) {
            echo $update . "\n\n";
            $update = null;
            if (empty($json_decoded)) {
                echo "Empty update: \n";
                echo $update . "\n\n";
                continue;
            }
            $telegram->processUpdate(new Longman\TelegramBot\Entities\Update($json_decoded, $BOT_NAME));
        }
        $update .= $current_line;
    }

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    echo $e;
}
