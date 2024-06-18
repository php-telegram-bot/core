<?php

require 'bootstrap.php';

$bot = new \PhpTelegramBot\Core\Telegram($_SERVER['BOT_TOKEN']);
$recipient = $_SERVER['RECIPIENT_CHAT_ID'];
$filepath = __DIR__.'/files/example_photo.jpeg';

try {

    $bot->sendMediaGroup([
        'chat_id' => $recipient,
        'media'   => [
            [
                'type'    => 'photo',
                'media'   => $filepath,
                'caption' => 'Photo #1',
            ],
            [
                'type'    => 'photo',
                'media'   => fopen($filepath, 'r'),
                'caption' => 'Photo #2',
            ],
            [
                'type'    => 'photo',
                'media'   => new \GuzzleHttp\Psr7\Stream(fopen($filepath, 'r')),
                'caption' => 'Photo #3',
            ],
        ],
    ]);

    echo 'Photos sent.';

} catch (\PhpTelegramBot\Core\Exceptions\TelegramException $e) {

    echo 'ERROR: '.$e->getMessage();

}
