<?php

require 'bootstrap.php';

$bot = new \PhpTelegramBot\Core\Telegram($_SERVER['BOT_TOKEN']);

try {

    $bot->sendPhoto([
        'chat_id' => $_SERVER['RECIPIENT_CHAT_ID'],
        ...\PhpTelegramBot\Core\Entities\InputFile::attachFile('photo', __DIR__.'/files/example_photo.jpeg'),
    ]);

    // or

    //    $bot->sendPhoto([
    //        'chat_id' => $_SERVER['RECIPIENT_CHAT_ID'],
    //    ] + \PhpTelegramBot\Core\Entities\InputFile::attachFile('photo', __DIR__.'/files/example_photo.jpeg'));

    echo 'Photo sent.';

} catch (\PhpTelegramBot\Core\Exceptions\TelegramException $e) {

    echo 'ERROR: '.$e->getMessage();

}
