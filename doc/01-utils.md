## Logging
PHP Telegram Bot library features [Monolog](https://github.com/Seldaek/monolog) to store logs.

Logs are divided into the following streams:
### Error
Collects all the exceptions thrown by the library:
```php
TelegramLog::initErrorLog($path . '/' . $BOT_NAME . '_error.log');
```

### Debug
Stores requests made to the Telegram API, useful for debugging:
```php
TelegramLog::initDebugLog($path . '/' . $BOT_NAME . '_debug.log');
```

### Raw data
Incoming updates (JSON string from Webhook and getUpdates) get logged in a text file:
```php
TelegramLog::initUpdateLog($path . '/' . $BOT_NAME . '_update.log');
```
Why do I need to log the raw updates?
Telegram API changes continuously and it often happens that the database schema is not up to date with new entities/features. So it can happen that your table schema doesn't allow storing new valuable information coming from Telegram.

If you store the raw data you can import all updates on the newest table schema by simply using [this script](../utils/importFromLog.php).
Remember to always backup first!!

## Stream and external sources
Error and Debug streams rely on the `bot_log` instance that can be provided from an external source:
```php
TelegramLog::initialize($monolog);
```

Raw data relies on the `bot_update_log` instance that uses a custom format.
