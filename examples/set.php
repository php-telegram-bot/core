<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$API_KEY = 'your_bot_api_key';
$BOT_USERNAME = 'username_bot';
$hook_url = 'https://yourdomain/path/to/hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_USERNAME);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);

    // Uncomment to use certificate
    //$result = $telegram->setWebhook($hook_url, ['certificate' => $path_certificate]);

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
