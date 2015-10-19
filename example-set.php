<?php
//Composer Loader
$loader = require __DIR__.'/vendor/autoload.php';

$API_KEY = 'your_bot_api_key';
$BOT_NAME = 'namebot';
$link = 'https://yourdomain/yourpath_to_hook.php';
try {
    // create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);
    // set webhook
    $result = $telegram->setWebHook($link);
    //Uncomment to use certificate
    //$result = $telegram->setWebHook($link, $path_certificate);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
