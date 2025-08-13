# Advanced Features

This section covers some of the more advanced features of the PHP Telegram Bot library that allow you to build more complex and interactive bots.

## 1. Database Integration (MySQL)

For features like conversations, tracking users, and remembering chat history, enabling MySQL is highly recommended.

### Setup

1.  **Create a Database:** Create a new MySQL database for your bot. Ensure it uses the `utf8mb4` character set to support all Telegram characters and emojis.
2.  **Import the Schema:** The library provides a `structure.sql` file that contains the necessary table structure. Import this file into your newly created database.
    ```bash
    mysql -u your_user -p your_database < structure.sql
    ```
3.  **Enable in your code:** In your main bot file (`getUpdates.php` or `hook.php`), provide your database credentials to the `enableMySql()` method. This should be done *before* handling updates.

    ```php
    <?php
    // ...
    $telegram = new Longman\TelegramBot\Telegram($api_key, $bot_username);

    $mysql_credentials = [
       'host'     => 'localhost',
       'port'     => 3306, // optional
       'user'     => 'your_user',
       'password' => 'your_password',
       'database' => 'your_database',
    ];

    $telegram->enableMySql($mysql_credentials);

    // ... handle updates
    ```

With the database enabled, the library will automatically log all incoming updates, messages, users, and chats.

## 2. Conversations

The conversation feature allows your bot to have multi-step interactions with users. For example, you can ask a user for their name, then their age, and then their location, all within a single command.

A conversation is essentially a command that remains "active" across multiple messages.

### Creating a Conversation Command

A conversation command is similar to a regular command but extends the `Conversation` class.

```php
<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Conversation;
// ... other `use` statements

class MyConversationCommand extends UserCommand
{
    // ... command properties ($name, $description, etc.)

    /**
     * @var Conversation
     */
    protected $conversation;

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $user_id = $message->getFrom()->getId();

        // Prepare the conversation
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        // ... conversation logic ...
    }
}
```

The key is the `Conversation` object, which stores the state of the conversation in the database. You can add notes to the conversation to track the user's progress and store their answers.

For a detailed guide and examples, please refer to the [official example in the example-bot repository](https://github.com/php-telegram-bot/example-bot/blob/master/Commands/ConversationCommand.php).

## 3. Logging

The library uses the [PSR-3](https://www.php-fig.org/psr/psr-3/) standard for logging, allowing you to use any compatible logger, such as [Monolog](https://github.com/Seldaek/monolog).

Logs are separated into three streams:
-   `error`: For exceptions and errors within the library.
-   `debug`: For detailed debugging information, including API requests and responses.
-   `update`: For the raw JSON updates received from Telegram. This is very useful for re-importing missed updates.

### Initializing the Logger

You should initialize the logger at the beginning of your script.

```php
use Longman\TelegramBot\TelegramLog;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

TelegramLog::initialize(
    // Main logger for debug and error logs
    new Logger('telegram_bot', [
        (new StreamHandler(__DIR__ . '/logs/debug.log', Logger::DEBUG)),
        (new StreamHandler(__DIR__ . '/logs/error.log', Logger::ERROR)),
    ]),
    // Optional logger for raw updates
    new Logger('telegram_bot_updates', [
        (new StreamHandler(__DIR__ . '/logs/updates.log', Logger::INFO)),
    ])
);
```

## 4. Sending Messages and Media

The `Request` class provides static methods for all available Telegram Bot API methods.

### Sending a Text Message

```php
use Longman\TelegramBot\Request;

Request::sendMessage([
    'chat_id' => $chat_id,
    'text'    => 'This is a test message.',
]);
```

### Sending a Local Photo

To send a file from your local server, you must encode it first.

```php
use Longman\TelegramBot\Request;

Request::sendPhoto([
    'chat_id' => $chat_id,
    'photo'   => Request::encodeFile('/path/to/your/image.jpg'),
    'caption' => 'This is a cool photo!',
]);
```

### Sending a Photo by URL or File ID

You can also send a photo by providing a public URL or a `file_id` of a photo that is already on Telegram's servers.

```php
// By URL
Request::sendPhoto(['chat_id' => $chat_id, 'photo' => 'https://example.com/photo.jpg']);

// By File ID
Request::sendPhoto(['chat_id' => $chat_id, 'photo' => 'FILE_ID_OF_EXISTING_PHOTO']);
```

The same principles apply to sending other types of media, such as `sendDocument`, `sendAudio`, `sendVideo`, etc.
