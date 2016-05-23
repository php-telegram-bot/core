## Logging
Telegram bot library feats monolog to store logs.

Logs are divided in those streams:
### Error
Collects all the exceptions throwned by the library:

```php
    $telegram->setErrorLog($path . '/' . $BOT_NAME . '_error.log');
```

### Debug
Stores Curl messages with the server, useful for debugging:

```php
    $telegram->setDebugLog($path . '/' . $BOT_NAME . '_debug.log');
```

### Raw data
Incoming updates (json string from webhook and getUpdates) can be logged in a text file. Set this option with the methods:
```php
    $telegram->setUpdateLog($path . '/' . $BOT_NAME . '_update.log');
```
Why I need raw log?  
Telegram api changes continuously and often happen that db schema is not uptodate with new entities/features. So can happen that your table schema would not be able to store valuable new information coming from Telegram.

If you store raw data you can port all updates on the newest table schema just using [this script](../utils/importFromLog.php).
Remember always backup first!!

## Stream and external sources
Error and Debug streams relies on the `bot_log` instance that can be provided from an external source:

```php
    $telegram->enableExternalLog($insert_ here_your_extenl_monolog_instance)
```

Raw data relies on the `bot_update_log` instance that feats a custom format for this kind of logs.


