## Logging
PHP Telegram Bot library features [PSR-3] compatible logging to store logs.

You can find a list of compatible packages that can be used on [Packagist][PSR-3-providers].

Logs are divided into the following streams:
- `error`: Collects all the exceptions thrown by the library.
- `debug`: Stores requests made to the Telegram API, useful for debugging.
- `update`: Incoming raw updates (JSON string from Webhook and getUpdates).

### Initialisation
To initialise the logger, you can pass any `LoggerInterface` objects to the `TelegramLog::initialize` method.

The first parameter is the main logger, the second one is used for the raw updates.

(in this example we're using [Monolog])
```php
use Longman\TelegramBot\TelegramLog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

TelegramLog::initialize(
    // Main logger that handles all 'debug' and 'error' logs.
    new Logger('telegram_bot', [
        (new StreamHandler('/path/to/debug_log_file', Logger::DEBUG))->setFormatter(new LineFormatter(null, null, true)),
        (new StreamHandler('/path/to/error_log_file', Logger::ERROR))->setFormatter(new LineFormatter(null, null, true)),
    ]),
    // Updates logger for raw updates.
    new Logger('telegram_bot_updates', [
        (new StreamHandler('/path/to/updates_log_file', Logger::INFO))->setFormatter(new LineFormatter('%message%' . PHP_EOL)),
    ])
);
```

### Raw data
Why do I need to log the raw updates?
Telegram API changes continuously and it often happens that the database schema is not up to date with new entities/features. So it can happen that your table schema doesn't allow storing new valuable information coming from Telegram.

If you store the raw data you can import all updates on the newest table schema by simply using [this script](../utils/importFromLog.php).
Remember to always backup first!!

### Always log request and response data
If you'd like to always log the request and response data to the debug log, even for successful requests, you can set the appropriate variable:
```php
\Longman\TelegramBot\TelegramLog::$always_log_request_and_response = true;
```

### Hiding API token from the log
By default, the API token is removed from the log, to prevent any mistaken leakage when posting logs online.
This behaviour can be changed by setting the appropriate variable:
```php
\Longman\TelegramBot\TelegramLog::$remove_bot_token = false;
```

[PSR-3]: https://www.php-fig.org/psr/psr-3
[PSR-3-providers]: https://packagist.org/providers/psr/log-implementation
[Monolog]: https://github.com/Seldaek/monolog
