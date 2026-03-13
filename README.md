# php-telegram-bot-plus

[![Latest Version on Packagist](https://img.shields.io/packagist/v/devrabie/php-telegram-bot-plus.svg)](https://packagist.org/packages/devrabie/php-telegram-bot-plus)
[![Software License](https://img.shields.io/github/license/devrabie/php-telegram-bot-plus)](LICENSE)
[![Issues](https://img.shields.io/github/issues/devrabie/php-telegram-bot-plus)](https://github.com/devrabie/php-telegram-bot-plus/issues)

A community-driven and updated fork of the popular `php-telegram-bot/core` library, with support for the latest Telegram Bot API features.

---

## 📖 Overview

`php-telegram-bot-plus` is an enhanced and actively maintained fork of the great `longman/php-telegram-bot` library. This project was created to continue its development after the original project stalled, ensuring full support for the latest Telegram Bot API features and providing ongoing bug fixes and improvements for the community.

Our goal is to be a drop-in replacement for existing users, while providing powerful new features for new developers.

## ✨ Why choose `php-telegram-bot-plus`?

- **🚀 Up-to-date:** Full support for the latest versions of the Telegram Bot API.
- **🛠️ Actively Maintained:** Bugs from the original version are actively being fixed.
- **🧩 New Features:** Adding powerful new features that were not previously available.
- **🤝 Community-Driven:** We welcome contributions from developers to make the library better for everyone.
- **🔒 Compatible:** We maintain backward compatibility as much as possible to make upgrading easy.

## 📦 Installation

```bash
composer require devrabie/php-telegram-bot-plus
```

## 🚀 Using the Redis Helper

This library provides a simple helper to integrate a Redis client, allowing you to easily use Redis for your custom data persistence needs (e.g., storing user states, settings, caching). The library itself remains stateless.

### 1. Enable Redis

In your main bot file (e.g., `hook.php` or your script that handles updates):

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$bot_api_key  = 'YOUR_BOT_API_KEY';
$bot_username = 'YOUR_BOT_USERNAME';

$telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

// Initialize the Redis client and make it available to all commands
// Default connection: 127.0.0.1:6379
$telegram->enableRedis();

// Or with custom connection parameters:
// $telegram->enableRedis([
//    'host'   => 'your-redis-host',
//    'port'   => 6379,
//    // 'password' => 'your-redis-password'
// ]);

// Handle updates
$telegram->handle();
```

### 2. Use Redis in Your Commands

You can access the shared Redis client instance from any command class using automatic dependency injection. Simply add a `redis` property to your command class and it will be automatically populated:
```php
<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class SettingsCommand extends UserCommand
{
    protected $name = 'settings';
    protected $description = 'Manage user settings using Redis';
    protected $usage = '/settings';
    protected $version = '1.0.0';

    /**
     * @var \Redis
     */
    protected $redis;

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        if ($this->redis) {
            $settings_key = 'bot:settings:' . $chat_id;

            // Example: Use Redis to store custom settings for a chat
            $this->redis->hset($settings_key, 'language', 'en');
            $lang = $this->redis->hget($settings_key, 'language');

            $text = 'Language set to: ' . $lang . ' (using Redis!)';
        } else {
            $text = 'Redis is not enabled.';
        }

        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text'    => $text,
        ]);
    }
}
```

You can also access the Redis instance statically from anywhere in your project:
```php
$redis = \Longman\TelegramBot\Telegram::getRedis();
```

### 3. Prevent Duplicate Updates

Enabling Redis also activates the built-in duplicate update prevention mechanism. This is useful when the bot receives the same update multiple times due to timeouts or retries from Telegram.

When Redis is enabled:
1.  The library checks if the `update_id` exists in Redis.
2.  If it exists, the update is ignored, and a fake success response is returned to Telegram to stop retries.
3.  If it doesn't exist, the `update_id` is stored in Redis with a retention time (TTL) of 60 seconds.

You can customize the retention time (in seconds):

```php
// Set retention time to 120 seconds
Longman\TelegramBot\Telegram::setUpdateRetentionTime(120);
```

---

## ⚙️ Advanced Configuration

### Request Timeout

To prevent your server from hanging on slow Telegram API requests (e.g., cURL error 28), you can set a custom timeout for the HTTP client. The default timeout is 60 seconds.

```php
use Longman\TelegramBot\Request;

// Set request timeout to 30 seconds
Request::setClientTimeout(30);
```

---

## 🔐 Webhook Secret Token

For enhanced security, you can set a secret token when you [set your webhook](https://core.telegram.org/bots/api#setwebhook). Telegram will then send this token in the `X-Telegram-Bot-Api-Secret-Token` header with every update. This library can automatically validate this token for you.

### 1. Set the Webhook with a Secret Token

When setting your webhook, provide a `secret_token`:

```php
$telegram->setWebhook('https://your-domain.com/hook.php', [
    'secret_token' => 'YOUR_SECRET_TOKEN',
]);
```

### 2. Configure Your Bot to Verify the Token

In your webhook handler (e.g., `hook.php`), set the same secret token on your `Telegram` object. The library will then automatically check the header on incoming requests and throw an exception if the token is missing or invalid.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$bot_api_key  = 'YOUR_BOT_API_KEY';
$bot_username = 'YOUR_BOT_USERNAME';
$bot_secret   = 'YOUR_SECRET_TOKEN';

try {
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set the secret token for incoming webhook requests
    $telegram->setSecretToken($bot_secret);

    // Handle the update
    $telegram->handle();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log the error
    error_log($e->getMessage());
}
```

This ensures that only requests from Telegram with the correct secret token are processed by your bot.

---

🙏 Acknowledgments
A huge thanks to the original developers of longman/php-telegram-bot for their incredible work that formed the foundation of this project.
