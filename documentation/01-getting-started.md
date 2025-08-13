# Getting Started

This guide will walk you through the initial steps to get your PHP Telegram Bot up and running.

## Requirements

- PHP 8.1 or higher
- The following PHP extensions:
    - `pdo`
    - `curl`
    - `json`
    - `mbstring`
- [Composer](https://getcomposer.org/) for dependency management.

## 1. Create a New Bot with BotFather

The first step is to register your new bot with Telegram. This is done by talking to a special bot called `@BotFather`.

1.  **Start a conversation:** Open Telegram and search for the contact `@BotFather`.
2.  **Send `/newbot`:** Type and send the `/newbot` command.
3.  **Choose a name:** BotFather will ask for a name for your bot. This is the display name, e.g., "My Awesome Bot".
4.  **Choose a username:** Next, choose a unique username for your bot. It must end in `bot`, for example: `MyAwesomeBot` or `my_awesome_bot`.
5.  **Receive your API Token:** BotFather will confirm the creation of your bot and provide you with an **API Token**. This token is essential for controlling your bot. Keep it safe and do not share it publicly.

**Example API Token:** `123456789:AAG90e14-0f8-40183D-18491dDE`

## 2. Install the Library

Once you have your API token, you can install the `longman/telegram-bot` library into your PHP project using Composer.

Navigate to your project directory and run the following command:

```bash
composer require longman/telegram-bot
```

This will download the library and its dependencies, and set up the autoloader.

## 3. Basic Configuration

To start using the library, you need to instantiate the main `Telegram` class with your API key and bot username.

Create a new PHP file (e.g., `bot.php`) and add the following code:

```php
<?php

// Load Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

// Your Bot API Key and Username
$api_key = 'YOUR_API_KEY';
$bot_username = 'YOUR_BOT_USERNAME';

try {
    // Create a new Telegram instance
    $telegram = new Longman\TelegramBot\Telegram($api_key, $bot_username);

    // Your bot's logic will go here

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log error
    error_log($e->getMessage());
}
```

Replace `YOUR_API_KEY` and `YOUR_BOT_USERNAME` with the credentials you received from BotFather.

You are now ready to start receiving updates and handling commands! Move on to the [Basic Usage](./02-basic-usage.md) guide to learn how.
