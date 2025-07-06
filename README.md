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

This library provides a simple helper to integrate a [Predis](https://github.com/predis/predis) client, allowing you to easily use Redis for your custom data persistence needs (e.g., storing user states, settings, caching). The library itself remains stateless.

### 1. Enable Redis

In your main bot file (e.g., `hook.php` or your script that handles updates):

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$bot_api_key  = 'YOUR_BOT_API_KEY';
$bot_username = 'YOUR_BOT_USERNAME';

$telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

// Initialize the Redis client and make it available to all commands
// Default connection: tcp://127.0.0.1:6379
$telegram->enableRedis();

// Or with custom connection parameters:
// $telegram->enableRedis([
//    'scheme' => 'tcp',
//    'host'   => 'your-redis-host',
//    'port'   => 6379,
//    // 'password' => 'your-redis-password'
// ]);

// Handle updates
$telegram->handle();
```

### 2. Use Redis in Your Commands

You can access the shared Redis client instance from any command class using `getRedis()`:

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

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        // Get the shared Redis client instance.
        /** @var \Predis\Client|null $redis */
        $redis = $this->getTelegram()->getRedis();

        if ($redis) {
            $settings_key = 'bot:settings:' . $chat_id;

            // Example: Use Redis to store custom settings for a chat
            $redis->hset($settings_key, 'language', 'en');
            $lang = $redis->hget($settings_key, 'language');

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

---

🙏 Acknowledgments
A huge thanks to the original developers of longman/php-telegram-bot for their incredible work that formed the foundation of this project.
