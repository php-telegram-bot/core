#!/usr/bin/env php
<?php
#bash script
#while true; do ./getUpdatesCLI.php; done

//Composer Loader
$dir = realpath(__DIR__.'/..');
$loader = require $dir.'/vendor/autoload.php';

$API_KEY = 'your_bot_api_key';
$BOT_NAME = 'namebot';
//$COMMANDS_FOLDER = __DIR__.'/Commands/';
$credentials = array(
    'host'=>'localhost',
    'user'=>'dbuser',
    'password'=>'dbpass',
    'database'=>'dbname'
);

try {
    // create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    //Options

    $telegram->enableMySQL($credentials);
    //$telegram->enableMySQL($credentials, $BOT_NAME.'_');
    //$telegram->addCommandsPath($COMMANDS_FOLDER);
    //here you can set some command specified parameters,
    //for example, google geocode/timezone api key for date command:
    //$telegram->setCommandConfig('date', array('google_api_key'=>'your_google_api_key_here'));
    //$telegram->setLogRequests(true);
    //$telegram->setLogPath($BOT_NAME.'.log');
    //$telegram->setLogVerbosity(3);
    //$telegram->setDownloadPath("../Download");
    //$telegram->setUploadPath("../Upload");

    // handle telegram getUpdate request
    $telegram->handleGetUpdates();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
     echo $e;
}
