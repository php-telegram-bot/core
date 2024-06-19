<?php

use PhpTelegramBot\Core\Entities\Update;
use PhpTelegramBot\Core\Events\IncomingUpdate;
use PhpTelegramBot\Core\Telegram;

require __DIR__ . '/bootstrap.php';

$bot = new Telegram($_SERVER['BOT_TOKEN']);

function save_dump(Update $update, Telegram $bot)
{
    $filename = __DIR__ . '/logs/' . uniqid(date('Y-m-d_H-i-s')) . '_update.json';
    file_put_contents($filename, json_encode($update, JSON_PRETTY_PRINT));
}

$bot->registerEventListener(IncomingUpdate::class, function (IncomingUpdate $event, Telegram $bot) {
    // Gets called on every incoming `Update` no matter what type
    dump('IncomingUpdate');
});

$bot->registerUpdateTypes([
    'edited_message' => function (Update $update, Telegram $bot) {
        // Gets called on every `Update` of type "edited_message"
        dump('edited_message');
    },
]);

$bot->registerMessageTypes([
    'text' => [
        function (Update $update, Telegram $bot) {
            // Gets called on every `Update` with a `Message` of type "text"
            dump('message.text');
            dump($update->getMessage()->getText());
        },
        'save_dump',
    ],

    'voice' => function (Update $update, Telegram $bot) {
        // Gets called on every `Update` with a `Message` of type "voice".
        dump('message.voice');
        dump($update->getMessage()->getVoice());
    },
]);

// Start 30s long polling in an infinite loop
// See https://en.wikipedia.org/wiki/Push_technology#Long_polling
$bot->handleGetUpdates();
