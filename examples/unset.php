<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$API_KEY = 'your_bot_api_key';
$BOT_USERNAME = 'username_bot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_USERNAME);

    // Delete webhook
    $result = $telegram->deleteWebhook();

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
