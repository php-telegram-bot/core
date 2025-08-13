# Basic Usage

There are two ways to receive updates from Telegram for your bot: the `getUpdates` method and the `webhook` method.

| Method | Description | Requirements |
| :--- | :--- | :--- |
| **getUpdates** | You manually fetch updates from Telegram by running a script. | Simple to set up, no HTTPS required. |
| **Webhook** | Telegram sends updates directly to your server via an HTTPS POST request. | Requires an HTTPS-enabled server. More efficient. |

---

## Method 1: getUpdates

This method is simpler for development and testing. You run a script that actively asks Telegram for new messages. For this method to be effective, it's recommended to enable MySQL integration to keep track of processed updates.

### `getUpdates` Example

Create a file named `getUpdates.php`:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$api_key = 'YOUR_API_KEY';
$bot_username = 'YOUR_BOT_USERNAME';

// Optional: MySQL credentials for storing update states
$mysql_credentials = [
   'host'     => 'localhost',
   'user'     => 'db_user',
   'password' => 'db_password',
   'database' => 'telegram_bot_db',
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($api_key, $bot_username);

    // Enable MySQL (recommended)
    $telegram->enableMySql($mysql_credentials);

    // Handle telegram getUpdates request
    $telegram->handleGetUpdates();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log Telegram errors
    error_log($e->getMessage());
}
```

To fetch updates, you need to execute this script periodically. You can do this manually for testing, or set up a cron job to run it automatically (e.g., every minute).

```bash
# Run the script manually
php getUpdates.php

# Run in a loop (for development)
while true; do php getUpdates.php; sleep 1; done
```

---

## Method 2: Webhook

This is the recommended method for production bots. You set a URL, and Telegram will send a POST request with the update data to that URL as soon as it arrives.

### Step 1: Set the Webhook

You only need to do this once. Create a file named `setWebhook.php`:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$api_key      = 'YOUR_API_KEY';
$bot_username = 'YOUR_BOT_USERNAME';

// The URL of your hook.php file
$hook_url     = 'https://your-domain.com/path/to/hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo 'Webhook was set successfully!';
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    error_log($e->getMessage());
}
```

Run this script once from your browser or command line to register your webhook with Telegram.

### Step 2: Handle Incoming Updates

Now, create the `hook.php` file that will receive the updates from Telegram.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$api_key      = 'YOUR_API_KEY';
$bot_username = 'YOUR_BOT_USERNAME';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($api_key, $bot_username);

    // Handle incoming webhook request
    $telegram->handle();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // Optionally, log the error
    // error_log($e->getMessage());
}
```

Now, your bot is set up to receive updates in real-time. The next step is to learn how to handle these updates with [Commands](./03-commands.md).
