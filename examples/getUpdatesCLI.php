#!/usr/bin/env php
<?php
//README
//This configuration file is intented to run the bot with the webhook method
//Uncommented parameters must be filled 

#bash script
#while true; do ./getUpdatesCLI.php; done

//Composer Loader
$dir = realpath(__DIR__.'/..');
$loader = require $dir.'/vendor/autoload.php';

$API_KEY = 'your_bot_api_key';
$BOT_NAME = 'namebot';
//$COMMANDS_FOLDER = __DIR__.'/Commands/';
$credentials = [
    'host'=>'localhost',
    'user'=>'dbuser',
    'password'=>'dbpass',
    'database'=>'dbname'
];

try {
    // create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    ////Options
    $telegram->enableMySQL($credentials);
     
    ////Enable mysql with table prefix
    //$telegram->enableMySQL($credentials, $BOT_NAME.'_');

    //$telegram->addCommandsPath($COMMANDS_FOLDER);

    ////Here you can enable admin interface ache the channel you want to manage
    //$telegram->enableAdmins(['your_telegram_id']);
    //$telegram->setCommandConfig('sendtochannel', ['your_channel'=>'@type_here_your_channel']);

    ////Here you can set some command specified parameters,
    ////for example, google geocode/timezone api key for date command:
    //$telegram->setCommandConfig('date', array('google_api_key'=>'your_google_api_key_here'));

    ////Logging
    //$telegram->setLogRequests(true);
    //$telegram->setLogPath($BOT_NAME.'.log');
    //$telegram->setLogVerbosity(3);

    //$telegram->setDownloadPath("../Download");
    //$telegram->setUploadPath("../Upload");

    // handle telegram getUpdate request
    $ServerResponse = $telegram->handleGetUpdates();

    if ($ServerResponse->isOk()) {
        $n_update = count($ServerResponse->getResult());
        
        print(date('Y-m-d H:i:s', time()).' - Processed '.$n_update." updates\n");
    } else {
        print(date('Y-m-d H:i:s', time())." - Fail fetch updates\n");
        echo $ServerResponse->printError()."\n";
    }

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
     echo $e;
}
