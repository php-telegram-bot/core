<h1 align="center">
    PHP Telegram Bot<br>
	<br>
    <img src="https://raw.githubusercontent.com/php-telegram-bot/assets/master/logo/512px/logo_plain.png" title="PHP Telegram Bot" alt="PHP Telegram Bot logo">
	<br>
</h1>

A Telegram Bot based on the official [Telegram Bot API]

[![API Version](https://img.shields.io/badge/Bot%20API-6.3%20%28November%202022%29-32a2da.svg)](https://core.telegram.org/bots/api#november-5-2022)
[![Join the bot support group on Telegram](https://img.shields.io/badge/telegram-@PHP__Telegram__Bot__Support-64659d.svg)](https://telegram.me/PHP_Telegram_Bot_Support)
[![Donate](https://img.shields.io/badge/%F0%9F%92%99-Donate%20%2F%20Support%20Us-blue.svg)](#donate)

[![Tests](https://github.com/php-telegram-bot/core/actions/workflows/tests.yaml/badge.svg)](https://github.com/php-telegram-bot/core/actions/workflows/tests.yaml)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/php-telegram-bot/core/master.svg?style=flat)](https://scrutinizer-ci.com/g/php-telegram-bot/core/?b=master)
[![Code Quality](https://img.shields.io/scrutinizer/g/php-telegram-bot/core/master.svg?style=flat)](https://scrutinizer-ci.com/g/php-telegram-bot/core/?b=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/longman/telegram-bot.svg)](https://packagist.org/packages/longman/telegram-bot)
[![Dependencies](https://tidelift.com/badges/github/php-telegram-bot/core?style=flat)][Tidelift]
[![Total Downloads](https://img.shields.io/packagist/dt/longman/telegram-bot.svg)](https://packagist.org/packages/longman/telegram-bot)
[![Downloads Month](https://img.shields.io/packagist/dm/longman/telegram-bot.svg)](https://packagist.org/packages/longman/telegram-bot)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D7.3-8892BF.svg)](https://php.net/)
[![License](https://img.shields.io/packagist/l/longman/telegram-bot.svg)](https://github.com/php-telegram-bot/core/LICENSE)

## Table of Contents
- [Introduction](#introduction)
- [Instructions](#instructions)
    - [Create your first bot](#create-your-first-bot)
    - [Require this package with Composer](#require-this-package-with-composer)
    - [Choose how to retrieve Telegram updates](#choose-how-to-retrieve-telegram-updates)
- [Using a custom Bot API server](#using-a-custom-bot-api-server)
- [Webhook installation](#webhook-installation)
    - [Self Signed Certificate](#self-signed-certificate)
    - [Unset Webhook](#unset-webhook)
- [getUpdates installation](#getupdates-installation)
    - [getUpdates without database](#getupdates-without-database)
- [Filter Update](#filter-update)
- [Support](#support)
    - [Types](#types)
    - [Inline Query](#inline-query)
    - [Methods](#methods)
    - [Send Message](#send-message)
    - [Send Photo](#send-photo)
    - [Send Chat Action](#send-chat-action)
    - [getUserProfilePhoto](#getuserprofilephoto)
    - [getFile and downloadFile](#getfile-and-downloadfile)
    - [Send message to all active chats](#send-message-to-all-active-chats)
- [Utils](#utils)
    - [MySQL storage (Recommended)](#mysql-storage-recommended)
        - [External Database connection](#external-database-connection)
    - [Channels Support](#channels-support)
- [Commands](#commands)
    - [Predefined Commands](#predefined-commands)
    - [Custom Commands](#custom-commands)
    - [Commands Configuration](#commands-configuration)
    - [Admin Commands](#admin-commands)
        - [Set Admins](#set-admins)
        - [Channel Administration](#channel-administration)
- [Upload and Download directory path](#upload-and-download-directory-path)
- [Logging](doc/01-utils.md)
- [Documentation](#documentation)
- [Assets](#assets)
- [Example bot](#example-bot)
- [Projects with this library](#projects-with-this-library)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [Security](#security)
- [Donate](#donate)
- [For enterprise](#for-enterprise)
- [License](#license)
- [Credits](#credits)

## Introduction

This is a pure PHP Telegram Bot, fully extensible via plugins.

Telegram announced official support for a [Bot API](https://telegram.org/blog/bot-revolution), allowing integrators of all sorts to bring automated interactions to the mobile platform.
This Bot aims to provide a platform where one can simply write a bot and have interactions in a matter of minutes.

The Bot can:
- Retrieve updates with [webhook](#webhook-installation) and [getUpdates](#getupdates-installation) methods.
- Supports all types and methods according to Telegram Bot API 6.3 (November 2022).
- Supports supergroups.
- Handle commands in chat with other bots.
- Manage Channel from the bot admin interface.
- Full support for **inline bots**.
- Inline keyboard.
- Messages, InlineQuery and ChosenInlineQuery are stored in the Database.
- Conversation feature.

---

This code is available on [GitHub](https://github.com/php-telegram-bot/core). Pull requests are welcome.

## Instructions

### Create your first bot

1. Message [`@BotFather`](https://telegram.me/BotFather) with the following text: `/newbot`

   If you don't know how to message by username, click the search field on your Telegram app and type `@BotFather`, where you should be able to initiate a conversation. Be careful not to send it to the wrong contact, because some users have similar usernames to `BotFather`.

   ![BotFather initial conversation](https://user-images.githubusercontent.com/9423417/60736229-bc2aeb80-9f45-11e9-8d35-5b53145347bc.png)

2. `@BotFather` replies with:

    ```
    Alright, a new bot. How are we going to call it? Please choose a name for your bot.
    ```

3. Type whatever name you want for your bot.

4. `@BotFather` replies with:

    ```
    Good. Now let's choose a username for your bot. It must end in `bot`. Like this, for example: TetrisBot or tetris_bot.
    ```

5. Type whatever username you want for your bot, minimum 5 characters, and must end with `bot`. For example: `telesample_bot`

6. `@BotFather` replies with:

    ```
    Done! Congratulations on your new bot. You will find it at
    telegram.me/telesample_bot. You can now add a description, about
    section and profile picture for your bot, see /help for a list of
    commands.

    Use this token to access the HTTP API:
    123456789:AAG90e14-0f8-40183D-18491dDE

    For a description of the Bot API, see this page:
    https://core.telegram.org/bots/api
    ```

7. Note down the 'token' mentioned above.

*Optionally set the bot privacy:*

1. Send `/setprivacy` to `@BotFather`.

   ![BotFather later conversation](https://user-images.githubusercontent.com/9423417/60736340-26439080-9f46-11e9-970f-8f33bbe39c5f.png)

2. `@BotFather` replies with:

    ```
    Choose a bot to change group messages settings.
    ```

3. Type (or select) `@telesample_bot` (change to the username you set at step 5
above, but start it with `@`)

4. `@BotFather` replies with:

    ```
    'Enable' - your bot will only receive messages that either start with the '/' symbol or mention the bot by username.
    'Disable' - your bot will receive all messages that people send to groups.
    Current status is: ENABLED
    ```

5. Type (or select) `Disable` to let your bot receive all messages sent to a group.

6. `@BotFather` replies with:

    ```
    Success! The new status is: DISABLED. /help
    ```

### Require this package with Composer

Install this package through [Composer].
Edit your project's `composer.json` file to require `longman/telegram-bot`.

Create *composer.json* file
```json
{
    "name": "yourproject/yourproject",
    "type": "project",
    "require": {
        "php": ">=7.3",
        "longman/telegram-bot": "*"
    }
}
```
and run `composer update`

**or**

run this command in your command line:

```bash
composer require longman/telegram-bot
```

### Choose how to retrieve Telegram updates

The bot can handle updates with [**Webhook**](#webhook-installation) or [**getUpdates**](#getupdates-installation) method:

|      | Webhook | getUpdates |
| ---- | :----: | :----: |
| Description | Telegram sends the updates directly to your host | You have to fetch Telegram updates manually |
| Host with https | Required | Not required |
| MySQL | Not required | ([Not](#getupdates-without-database)) Required  |

## Using a custom Bot API server

**For advanced users only!**

As from Telegram Bot API 5.0, users can [run their own Bot API server] to handle updates.
This means, that the PHP Telegram Bot needs to be configured to serve that custom URI.
Additionally, you can define the URI where uploaded files to the bot can be downloaded (note the `{API_KEY}` placeholder).

```php
Longman\TelegramBot\Request::setCustomBotApiUri(
    $api_base_uri          = 'https://your-bot-api-server', // Default: https://api.telegram.org
    $api_base_download_uri = '/path/to/files/{API_KEY}'     // Default: /file/bot{API_KEY}
);
```

**Note:** If you are running your bot in `--local` mode, you won't need the `Request::downloadFile()` method, since you can then access your files directly from the absolute path returned by `Request::getFile()`.

## Webhook installation

Note: For a more detailed explanation, head over to the [example-bot repository] and follow the instructions there.

In order to set a [Webhook][api-setwebhook] you need a server with HTTPS and composer support.
(For a [self signed certificate](#self-signed-certificate) you need to add some extra code)

Create *[set.php]* with the following contents:
```php
<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = 'your:bot_api_key';
$bot_username = 'username_bot';
$hook_url     = 'https://your-domain/path/to/hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}
```

Open your *set.php* via the browser to register the webhook with Telegram.
You should see `Webhook was set`.

Now, create *[hook.php]* with the following contents:
```php
<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = 'your:bot_api_key';
$bot_username = 'username_bot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e->getMessage();
}
```

### Self Signed Certificate

Upload the certificate and add the path as a parameter in *set.php*:
```php
$result = $telegram->setWebhook($hook_url, ['certificate' => '/path/to/certificate']);
```

### Unset Webhook

Edit *[unset.php]* with your bot credentials and execute it.

## getUpdates installation

For best performance, the MySQL database should be enabled for the `getUpdates` method!

Create *[getUpdatesCLI.php]* with the following contents:
```php
#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = 'your:bot_api_key';
$bot_username = 'username_bot';

$mysql_credentials = [
   'host'     => 'localhost',
   'port'     => 3306, // optional
   'user'     => 'dbuser',
   'password' => 'dbpass',
   'database' => 'dbname',
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Enable MySQL
    $telegram->enableMySql($mysql_credentials);

    // Handle telegram getUpdates request
    $telegram->handleGetUpdates();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}
```

Next, give the file permission to execute:
```bash
$ chmod +x getUpdatesCLI.php
```

Lastly, run it!
```bash
$ ./getUpdatesCLI.php
```

### getUpdates without database

If you choose to / or are obliged to use the `getUpdates` method without a database, you can replace the `$telegram->useMySQL(...);` line above with:
```php
$telegram->useGetUpdatesWithoutDatabase();
```

## Filter Update

:exclamation: Note that by default, Telegram will send any new update types that may be added in the future. This may cause commands that don't take this into account to break!

It is suggested that you specifically define which update types your bot can receive and handle correctly.

You can define which update types are sent to your bot by defining them when setting the [webhook](#webhook-installation) or passing an array of allowed types when using [getUpdates](#getupdates-installation).

```php
use Longman\TelegramBot\Entities\Update;

// For all update types currently implemented in this library:
// $allowed_updates = Update::getUpdateTypes();

// Define the list of allowed Update types manually:
$allowed_updates = [
    Update::TYPE_MESSAGE,
    Update::TYPE_CHANNEL_POST,
    // etc.
];

// When setting the webhook.
$telegram->setWebhook($hook_url, ['allowed_updates' => $allowed_updates]);

// When handling the getUpdates method.
$telegram->handleGetUpdates(['allowed_updates' => $allowed_updates]);
```

Alternatively, Update processing can be allowed or denied by defining a custom update filter.

Let's say we only want to allow messages from a user with ID `428`, we can do the following before handling the request:

```php
$telegram->setUpdateFilter(function (Update $update, Telegram $telegram, &$reason = 'Update denied by update_filter') {
    $user_id = $update->getMessage()->getFrom()->getId();
    if ($user_id === 428) {
        return true;
    }

    $reason = "Invalid user with ID {$user_id}";
    return false;
});
```

The reason for denying an update can be defined with the `$reason` parameter. This text gets written to the debug log.

## Support

### Types

All types are implemented according to Telegram API 6.3 (November 2022).

### Inline Query

Full support for inline query according to Telegram API 6.3 (November 2022).

### Methods

All methods are implemented according to Telegram API 6.3 (November 2022).

#### Send Message

Messages longer than 4096 characters are split up into multiple messages.

```php
$result = Request::sendMessage([
    'chat_id' => $chat_id,
    'text'    => 'Your utf8 text 😜 ...',
]);
```

#### Send Photo

To send a local photo, add it properly to the `$data` parameter using the file path:

```php
$result = Request::sendPhoto([
    'chat_id' => $chat_id,
    'photo'   => Request::encodeFile('/path/to/pic.jpg'),
]);
```

If you know the `file_id` of a previously uploaded file, just use it directly in the data array:

```php
$result = Request::sendPhoto([
    'chat_id' => $chat_id,
    'photo'   => 'AAQCCBNtIhAoAAss4tLEZ3x6HzqVAAqC',
]);
```

To send a remote photo, use the direct URL instead:

```php
$result = Request::sendPhoto([
    'chat_id' => $chat_id,
    'photo'   => 'https://example.com/path/to/pic.jpg',
]);
```

*sendAudio*, *sendDocument*, *sendAnimation*, *sendSticker*, *sendVideo*, *sendVoice* and *sendVideoNote* all work in the same way, just check the [API documentation](https://core.telegram.org/bots/api#sendphoto) for the exact usage.
See the *[ImageCommand.php]* for a full example.

#### Send Chat Action

```php
Request::sendChatAction([
    'chat_id' => $chat_id,
    'action'  => Longman\TelegramBot\ChatAction::TYPING,
]);
```

#### getUserProfilePhoto

Retrieve the user photo. (see *[WhoamiCommand.php]* for a full example)

#### getFile and downloadFile

Get the file path and download it. (see *[WhoamiCommand.php]* for a full example)

#### Send message to all active chats

To do this you have to enable the MySQL connection.
Here's an example of use (check [`DB::selectChats()`][DB::selectChats] for parameter usage):

```php
$results = Request::sendToActiveChats(
    'sendMessage', // Callback function to execute (see Request.php methods)
    ['text' => 'Hey! Check out the new features!!'], // Param to evaluate the request
    [
        'groups'      => true,
        'supergroups' => true,
        'channels'    => false,
        'users'       => true,
    ]
);
```

You can also broadcast a message to users, from the private chat with your bot. Take a look at the [admin commands](#admin-commands) below.

## Utils

### MySQL storage (Recommended)

If you want to save messages/users/chats for further usage in commands, create a new database (`utf8mb4_unicode_520_ci`), import *[structure.sql]* and enable MySQL support BEFORE `handle()` method:

```php
$mysql_credentials = [
   'host'     => 'localhost',
   'port'     => 3306, // optional
   'user'     => 'dbuser',
   'password' => 'dbpass',
   'database' => 'dbname',
];

$telegram->enableMySql($mysql_credentials);
```

You can set a custom prefix to all the tables while you are enabling MySQL:

```php
$telegram->enableMySql($mysql_credentials, $bot_username . '_');
```

You can also store inline query and chosen inline query data in the database.

#### External Database connection

It is possible to provide the library with an external MySQL PDO connection.
Here's how to configure it:

```php
$telegram->enableExternalMySql($external_pdo_connection);
//$telegram->enableExternalMySql($external_pdo_connection, $table_prefix)
```
### Channels Support

All methods implemented can be used to manage channels.
With [admin commands](#admin-commands) you can manage your channels directly with your bot private chat.

## Commands

### Predefined Commands

The bot is able to recognise commands in a chat with multiple bots (/command@mybot).

It can also execute commands that get triggered by events, so-called Service Messages.

### Custom Commands

Maybe you would like to develop your own commands.
There is a guide to help you [create your own commands][wiki-create-your-own-commands].

Also, be sure to have a look at the [example commands][ExampleCommands-folder] to learn more about custom commands and how they work.

You can add your custom commands in different ways:

```php
// Add a folder that contains command files
$telegram->addCommandsPath('/path/to/command/files');
//$telegram->addCommandsPaths(['/path/to/command/files', '/another/path']);

// Add a command directly using the class name
$telegram->addCommandClass(MyCommand::class);
//$telegram->addCommandClasses([MyCommand::class, MyOtherCommand::class]);
```

### Commands Configuration

With this method you can set some command specific parameters, for example:

```php
// Google geocode/timezone API key for /date command
$telegram->setCommandConfig('date', [
    'google_api_key' => 'your_google_api_key_here',
]);

// OpenWeatherMap API key for /weather command
$telegram->setCommandConfig('weather', [
    'owm_api_key' => 'your_owm_api_key_here',
]);
```

### Admin Commands

Enabling this feature, the bot admin can perform some super user commands like:
- List all the chats started with the bot */chats*
- Clean up old database entries */cleanup*
- Show debug information about the bot */debug*
- Send message to all chats */sendtoall*
- Post any content to your channels */sendtochannel*
- Inspect a user or a chat with */whois*

Take a look at all default admin commands stored in the *[src/Commands/AdminCommands/][AdminCommands-folder]* folder.

#### Set Admins

You can specify one or more admins with this option:

```php
// Single admin
$telegram->enableAdmin(your_telegram_user_id);

// Multiple admins
$telegram->enableAdmins([
    your_telegram_user_id,
    other_telegram_user_id,
]);
```
Telegram user id can be retrieved with the *[/whoami][WhoamiCommand.php]* command.

#### Channel Administration

To enable this feature follow these steps:
- Add your bot as channel administrator, this can be done with any Telegram client.
- Enable admin interface for your user as explained in the admin section above.
- Enter your channel name as a parameter for the *[/sendtochannel][SendtochannelCommand.php]* command:
```php
$telegram->setCommandConfig('sendtochannel', [
    'your_channel' => [
        '@type_here_your_channel',
    ]
]);
```
- If you want to manage more channels:
```php
$telegram->setCommandConfig('sendtochannel', [
    'your_channel' => [
        '@type_here_your_channel',
        '@type_here_another_channel',
        '@and_so_on',
    ]
]);
```
- Enjoy!

## Upload and Download directory path

To use the Upload and Download functionality, you need to set the paths with:
```php
$telegram->setDownloadPath('/your/path/Download');
$telegram->setUploadPath('/your/path/Upload');
```

## Documentation

Take a look at the repo [Wiki] for further information and tutorials!
Feel free to improve!

## Assets

All project assets can be found in the [assets](https://github.com/php-telegram-bot/assets) repository.

## Example bot

We're busy working on a full A-Z example bot, to help get you started with this library and to show you how to use all its features.
You can check the progress of the [example-bot repository]).

## Projects with this library

Here's a list of projects that feats this library, feel free to add yours!
- [Inline Games](https://github.com/jacklul/inlinegamesbot) ([@inlinegamesbot](https://telegram.me/inlinegamesbot))
- [Super-Dice-Roll](https://github.com/RafaelDelboni/Super-Dice-Roll) ([@superdiceroll_bot](https://telegram.me/superdiceroll_bot))
- [tg-mentioned-bot](https://github.com/gruessung/tg-mentioned-bot)
- [OSMdeWikiBot](https://github.com/OSM-de/TelegramWikiBot) ([@OSM_de](https://t.me/OSM_de))
- [pass-generator-webbot](https://github.com/OxMohsen/pass-generator-webbot)
- [Chess Quiz Bot](https://github.com/1int/chess-quiz-bot)

## Troubleshooting

If you like living on the edge, please report any bugs you find on the [PHP Telegram Bot issues][issues] page.

## Contributing

See [CONTRIBUTING](CONTRIBUTING.md) for more information.

## Security

See [SECURITY](SECURITY.md) for more information.

## Donate

All work on this bot consists of many hours of coding during our free time, to provide you with a Telegram Bot library that is easy to use and extend.
If you enjoy using this library and would like to say thank you, donations are a great way to show your support.

Donations are invested back into the project :+1:

Thank you for keeping this project alive :pray:

- [![Patreon](https://user-images.githubusercontent.com/9423417/59235980-a5fa6b80-8be3-11e9-8ae7-020bc4ae9baa.png) Patreon.com/phptelegrambot][Patreon]
- [![OpenCollective](https://user-images.githubusercontent.com/9423417/59235978-a561d500-8be3-11e9-89be-82ec54be1546.png) OpenCollective.com/php-telegram-bot][OpenCollective]
- [![Ko-fi](https://user-images.githubusercontent.com/9423417/59235976-a561d500-8be3-11e9-911d-b1908c3e6a33.png) Ko-fi.com/phptelegrambot][Ko-fi]
- [![Tidelift](https://user-images.githubusercontent.com/9423417/59235982-a6930200-8be3-11e9-8ac2-bfb6991d80c5.png) Tidelift.com/longman/telegram-bot][Tidelift]
- [![Liberapay](https://user-images.githubusercontent.com/9423417/59235977-a561d500-8be3-11e9-9d16-bc3b13d3ceba.png) Liberapay.com/PHP-Telegram-Bot][Liberapay]
- [![PayPal](https://user-images.githubusercontent.com/9423417/59235981-a5fa6b80-8be3-11e9-9761-15eb7a524cb0.png) PayPal.me/noplanman][PayPal-noplanman] (account of @noplanman)
- [![Bitcoin](https://user-images.githubusercontent.com/9423417/59235974-a4c93e80-8be3-11e9-9fde-260c821b6eae.png) 166NcyE7nDxkRPWidWtG1rqrNJoD5oYNiV][Bitcoin]
- [![Ethereum](https://user-images.githubusercontent.com/9423417/59235975-a4c93e80-8be3-11e9-8762-7a47c62c968d.png) 0x485855634fa212b0745375e593fAaf8321A81055][Ethereum]

## For enterprise

Available as part of the Tidelift Subscription.

The maintainers of `PHP Telegram Bot` and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.][Tidelift]

## License

Please see the [LICENSE](LICENSE) included in this repository for a full copy of the MIT license, which this project is licensed under.

## Credits

Credit list in [CREDITS](CREDITS)

---

[Telegram Bot API]: https://core.telegram.org/bots/api "Telegram Bot API"
[Composer]: https://getcomposer.org/ "Composer"
[run their own Bot API server]: https://core.telegram.org/bots/api#using-a-local-bot-api-server "Using a Local Bot API Server"
[example-bot repository]: https://github.com/php-telegram-bot/example-bot "Example Bot repository"
[api-setwebhook]: https://core.telegram.org/bots/api#setwebhook "Webhook on Telegram Bot API"
[set.php]: https://github.com/php-telegram-bot/example-bot/blob/master/set.php "example set.php"
[unset.php]: https://github.com/php-telegram-bot/example-bot/blob/master/unset.php "example unset.php"
[hook.php]: https://github.com/php-telegram-bot/example-bot/blob/master/hook.php "example hook.php"
[getUpdatesCLI.php]: https://github.com/php-telegram-bot/example-bot/blob/master/getUpdatesCLI.php "example getUpdatesCLI.php"
[AdminCommands-folder]: https://github.com/php-telegram-bot/core/tree/master/src/Commands/AdminCommands "Admin commands folder"
[ExampleCommands-folder]: https://github.com/php-telegram-bot/example-bot/blob/master/Commands "Example commands folder"
[ImageCommand.php]: https://github.com/php-telegram-bot/example-bot/blob/master/Commands/Other/ImageCommand.php "example /image command"
[WhoamiCommand.php]: https://github.com/php-telegram-bot/example-bot/blob/master/Commands/WhoamiCommand.php "example /whoami command"
[HelpCommand.php]: https://github.com/php-telegram-bot/example-bot/blob/master/Commands/HelpCommand.php "example /help command"
[SendtochannelCommand.php]: https://github.com/php-telegram-bot/core/blob/master/src/Commands/AdminCommands/SendtochannelCommand.php "/sendtochannel admin command"
[DB::selectChats]: https://github.com/php-telegram-bot/core/blob/0.70.0/src/DB.php#L1148 "DB::selectChats() parameters"
[structure.sql]: https://github.com/php-telegram-bot/core/blob/master/structure.sql "DB structure for importing"
[Wiki]: https://github.com/php-telegram-bot/core/wiki "PHP Telegram Bot Wiki"
[wiki-create-your-own-commands]: https://github.com/php-telegram-bot/core/wiki/Create-your-own-commands "Create your own commands"
[issues]: https://github.com/php-telegram-bot/core/issues "PHP Telegram Bot Issues"

[Patreon]: https://www.patreon.com/phptelegrambot "Support us on Patreon"
[OpenCollective]: https://opencollective.com/php-telegram-bot "Support us on Open Collective"
[Ko-fi]: https://ko-fi.com/phptelegrambot "Support us on Ko-fi"
[Tidelift]: https://tidelift.com/subscription/pkg/packagist-longman-telegram-bot?utm_source=packagist-longman-telegram-bot&utm_medium=referral&utm_campaign=enterprise&utm_term=repo "Learn more about the Tidelift Subscription"
[Liberapay]: https://liberapay.com/PHP-Telegram-Bot "Donate with Liberapay"
[PayPal-noplanman]: https://paypal.me/noplanman "Donate with PayPal"
[Bitcoin]: https://www.blockchain.com/btc/address/166NcyE7nDxkRPWidWtG1rqrNJoD5oYNiV "Donate with Bitcoin"
[Ethereum]: https://etherscan.io/address/0x485855634fa212b0745375e593fAaf8321A81055 "Donate with Ethereum"
