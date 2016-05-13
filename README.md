# PHP Telegram Bot

[![Join the chat at https://gitter.im/akalongman/php-telegram-bot](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/akalongman/php-telegram-bot?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Build Status](https://travis-ci.org/akalongman/php-telegram-bot.svg?branch=master)](https://travis-ci.org/akalongman/php-telegram-bot)
[![Latest Stable Version](https://img.shields.io/packagist/v/Longman/telegram-bot.svg)](https://packagist.org/packages/longman/telegram-bot)
[![Total Downloads](https://img.shields.io/packagist/dt/Longman/telegram-bot.svg)](https://packagist.org/packages/longman/telegram-bot)
[![Downloads Month](https://img.shields.io/packagist/dm/Longman/telegram-bot.svg)](https://packagist.org/packages/longman/telegram-bot)
[![License](https://img.shields.io/packagist/l/Longman/telegram-bot.svg)](https://github.com/akalongman/php-telegram-bot/LICENSE.md)


A Telegram Bot based on the official [Telegram Bot API](https://core.telegram.org/bots/api)

## Table of Contents
- [Introduction](#introduction)
- [Instructions](#instructions)
    - [Create your first bot](#create-your-first-bot)
    - [Require this package with Composer](#require-this-package-with-composer)
    - [Choose how to retrieve Telegram updates](#choose-how-to-retrieve-telegram-updates)
    - [Webhook installation](#webhook-installation)
    - [Self Signed Certificate](#self-signed-certificate)
    - [Unset Webhook](#unset-webhook)
    - [getUpdate installation](#getupdate-installation)
- [Support](#support)
    - [Types](#types)
    - [Inline Query](#inline-query)
    - [Methods](#methods)
    - [Send Message](#send-message)
    - [Send Photo](#send-photo)
    - [Send Chat Action](#send-chat-action)
    - [getUserProfilePhoto](#getuserprofilephoto)
    - [getFile and dowloadFile](#getfile-and-dowloadfile)
    - [Send message to all active chats](#send-message-to-all-active-chats)
- [Utils](#utils)
    - [MySQL storage (Recommended)](#mysql-storage-recommended)
    - [Channels Support](#channels-support)
- [Commands](#commands)
    - [Predefined Commands](#predefined-commands)
    - [Custom Commands](#custom-commands)
    - [Commands Configuration](#commands-configuration)
- [Admin Commands](#admin-commands)
    - [Set Admins](#set-admins)
    - [Channel Administration](#channel-administration)
- [Upload and Download directory path](#upload-and-download-directory-path)
- [Logging](#logging)
- [Documentation](#documentation)
- [Projects with this library](#projects-with-this-library)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)
- [Credits](#credits)






## Introduction

This is a pure PHP Telegram Bot, fully extensible via plugins.
Telegram recently announced official support for a [Bot
API](https://telegram.org/blog/bot-revolution) allowing integrators of
all sorts to bring automated interactions to the mobile platform. This
Bot aims to provide a platform where one can simply write a plugin
and have interactions in a matter of minutes.

The Bot can:
- retrieve updates with webhook and getUpdate methods.
- supports all types and methods according to Telegram API (6 May 2016).
- supports supergroups.
- handle commands in chat with other bots.
- manage Channel from the bot admin interface.
- full support for **inline bots**. 
- inline keyboard.
- Messages, InlineQuery and ChosenInlineQuery are stored in the Database.
- Conversation feature (**new!**)

-----
This code is available on
[Github](https://github.com/akalongman/php-telegram-bot). Pull requests are welcome.

## Instructions

### Create your first bot

1. Message @botfather https://telegram.me/botfather with the following
text: `/newbot`
   If you don't know how to message by username, click the search
field on your Telegram app and type `@botfather`, where you should be able
to initiate a conversation. Be careful not to send it to the wrong
contact, because some users has similar usernames to `botfather`.

   ![botfather initial conversation](http://i.imgur.com/aI26ixR.png)

2. @botfather replies with `Alright, a new bot. How are we going to
call it? Please choose a name for your bot.`

3. Type whatever name you want for your bot.

4. @botfather replies with ```Good. Now let's choose a username for your
bot. It must end in `bot`. Like this, for example: TetrisBot or
tetris_bot.```

5. Type whatever username you want for your bot, minimum 5 characters,
and must end with `bot`. For example: `telesample_bot`

6. @botfather replies with:

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

8. Type `/setprivacy` to @botfather.

   ![botfather later conversation](http://i.imgur.com/tWDVvh4.png)

9. @botfather replies with `Choose a bot to change group messages settings.`

10. Type (or select) `@telesample_bot` (change to the username you set at step 5
above, but start it with `@`)

11. @botfather replies with

    ```
    'Enable' - your bot will only receive messages that either start with the '/' symbol or mention the bot by username.
    'Disable' - your bot will receive all messages that people send to groups.
    Current status is: ENABLED
    ```

12. Type (or select) `Disable` to let your bot receive all messages sent to a
group. This step is up to you actually.

13. @botfather replies with `Success! The new status is: DISABLED. /help`

### Require this package with Composer

Install this package through [Composer](https://getcomposer.org/).
Edit your project's `composer.json` file to require
`longman/telegram-bot`.

Create *composer.json* file
```js
{
    "name": "yourproject/yourproject",
    "type": "project",
    "require": {
        "php": ">=5.5.0",
        "longman/telegram-bot": "*"
    }
}
```
and run `composer update`

**or**

run this command in your command line:

```
composer require longman/telegram-bot
```

### Choose how to retrieve Telegram updates

The bot can handle updates with **webhook** or **getUpdate** method:

|      | Webhook | getUpdate |
| ---- | :----: | :----: |
| Description | Telegram sends the updates directly to your host | You have to fetch Telegram updates manually |
| Host with https | Required | Not required |
| MySQL | Not required | Required  |


## Webhook installation

In order to set a [Webhook](https://core.telegram.org/bots/api#setwebhook) you need a server with https and composer support.
(For a [self signed certificate](#self-signed-certificate) you need to add some extra code)

Create *set.php* (or just copy and edit *examples/set.php*) and put into it:
```php
<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$API_KEY = 'your_bot_api_key';
$BOT_NAME = 'namebot';
$hook_url = 'https://yourdomain/path/to/hook.php';
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    // Set webhook
    $result = $telegram->setWebHook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
```

Open your *set.php* via the browser to register the webhook with Telegram.

Now, create *hook.php* (or just copy and edit *examples/hook.php*) and put into it:
```php
<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$API_KEY = 'your_bot_api_key';
$BOT_NAME = 'namebot';
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e;
}
```

### Self Signed Certificate

To upload the certificate, add the certificate path as a parameter in *set.php*:
```php
$result = $telegram->setWebHook($hook_url, $certificate_path);
```

### Unset Webhook

Edit *example/unset.php* with your bot credentials and execute it.

### getUpdate installation

The MySQL database must be active!

Create *getUpdateCLI.php* (or just copy and edit *examples/getUpdateCLI.php*) and put into it:
```php
#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

$API_KEY = 'your_bot_api_key';
$BOT_NAME = 'namebot';
$mysql_credentials = [
   'host'     => 'localhost',
   'user'     => 'dbuser',
   'password' => 'dbpass',
   'database' => 'dbname',
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    // Enable MySQL
    $telegram->enableMySQL($mysql_credentials);

    // Handle telegram getUpdate request
    $telegram->handleGetUpdates();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    echo $e;
}
```

give the file permission to execute:
```
chmod 775 getUpdateCLI.php
```
then run
```
./getUpdateCLI.php
```

## Support

### Types

All types are implemented according to Telegram API (20 January 2016).

### Inline Query

Full support for inline query according to Telegram API (20 January 2016).

### Methods

All methods are implemented according to Telegram API (20 January 2016).

#### Send Message

Messages longer than 4096 characters are split up into multiple messages.

```php
$result = Request::sendMessage(['chat_id' => $chat_id, 'text' => 'Your utf8 text 😜 ...']);
```

#### Send Photo

To send a local photo, provide the file path as the second parameter:

```php
$data = ['chat_id' => $chat_id];
$result = Request::sendPhoto($data, $telegram->getUploadPath() . '/image.jpg');
```

If you know the `file_id` of a previously uploaded file, just include it in the data array:

```php
$data = [
    'chat_id' => $chat_id,
    'photo'   => $file_id,
];
$result = Request::sendPhoto($data);
```

*sendAudio*, *sendDocument*, *sendSticker*, *sendVideo* and *sendVoice* all work in the same way.
See *examples/Commands/ImageCommand.php* for a full example.

#### Send Chat Action

```php
Request::sendChatAction(['chat_id' => $chat_id, 'action' => 'typing']);
```

#### getUserProfilePhoto

Retrieve the user photo, see *src/Commands/WhoamiCommand.php* for a full example.

#### getFile and dowloadFile

Get the file path and download it, see *src/Commands/WhoamiCommand.php* for a full example.

#### Send message to all active chats

To do this you have to enable the MySQL connection.
Here's an example of use:

```php
$results = Request::sendToActiveChats(
        'sendMessage', // callback function to execute (see Request.php for available methods)
        ['text' => 'Hey! Check out the new features!!'], // Data to pass to the request
        true, // Send to chats (group chat)
        true, // Send to chats (super group chat)
        true, // Send to users (single chat)
        null, // 'yyyy-mm-dd hh:mm:ss' date range from
        null, // 'yyyy-mm-dd hh:mm:ss' date range to
    );
```

You can also broadcast a message to users, from the private chat with your bot. Take a look at the [admin commands](#admin-commands) below.

## Utils

### MySQL storage (Recommended)

If you want to save messages/users/chats for further usage
in commands, create a new database, import *structure.sql* and enable
MySQL support after object creation and BEFORE handle method:

```php
$mysql_credentials = [
   'host'     => 'localhost',
   'user'     => 'dbuser',
   'password' => 'dbpass',
   'database' => 'dbname',
];

$telegram->enableMySQL($mysql_credentials);
```
You can set a custom prefix to all the tables while you are enabling MySQL:

```php
$telegram->enableMySQL($mysql_credentials, $BOT_NAME . '_');
```

Consider to use the *utf8mb4* branch if you find some special characters problems.
You can also store inline query and chosen inline query in the database.
#### External Database connection
Is possible to provide to the library an external mysql connection. Here's how to configure it:

```php
$telegram->enableExternalMysql($external_pdo_connection)
//$telegram->enableExternalMySQL($external_pdo_connection, $table_prefix)
```
### Channels Support

All methods implemented can be used to manage channels.
With [admin commands](#admin-commands) you can manage your channels directly with your bot private chat.

### Commands

#### Predefined Commands

The bot is able to recognise commands in a chat with multiple bots (/command@mybot).
It can execute command triggering a chat event. Here's the list:

- New chat participant (**NewchatparticipantCommand.php**)
- Left chat participant (**LeftchatparticipantCommand.php**)
- New chat title (**NewchattitleCommand.php**)
- Delete chat photo (**DeletechatphotoCommand.php**)
- Group chat created (**GroupchatcreatedCommand.php**)
- Super group chat created (**SupergroupchatcreatedCommand.php**)
- Channel chat created (**ChannelchatcreatedCommand.php**)
- Inline query (**InlinequeryCommand.php**)
- Chosen inline result (**ChoseninlineresultCommand.php**)

**GenericCommand.php** lets you handle commands that don't exist or to
use commands as a variable:

Favourite colour? **/black, /red**

Favourite number? **/1, /134**

**GenericmessageCommand.php** lets you handle any type of message.

#### Custom Commands

Maybe you would like to develop your own commands. A good practice is
to store them outside *vendor/*. This can be done using:

```php
$commands_folder = __DIR__ . '/Commands/';
$telegram->addCommandsPath($commands_folder);
```

Inside *examples/Commands/* there are some samples that show how to use types.

#### Commands Configuration

With this method you can set some command specific parameters, for
example, google geocode/timezone api key for date command:
```php
$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);
```

### Admin Commands

Enabling this feature, the admin bot can perform some super user commands like:
- Send message to all chats */sendtoall*
- List all the chats started with the bot */chats*
- Post any content to your channels */sendtochannel* 
- inspect a user or a chat wiht */whois* (new!)

#### Set Admins

You can specify one or more admins with this option:

```php
$telegram->enableAdmins(['your_telegram_user_id', 'other_telegram_user_id']);
```
Telegram user id can be retrieved with the command **/whoami**.
Admin commands are stored in *src/Admin/* folder.
To get a list of all available commands, type **/help**.

#### Channel Administration

To enable this feature follow these steps:
- Add your bot as channel administrator, this can be done with any telegram client.
- Enable admin interface for your user as explained in the admin section above.
- Enter your channel name as a parameter for the */sendtochannel* command:
```php
$telegram->setCommandConfig('sendtochannel', ['your_channel' => ['@type_here_your_channel']]);
```
- If you want to manage more channels:
```php
$telegram->setCommandConfig('sendtochannel', ['your_channel'=>['@type_here_your_channel', '@type_here_another_channel', '@and_so_on']]);
```
- Enjoy!

### Upload and Download directory path

You can override the default Upload and Download directory with:
```php
$telegram->setDownloadPath('yourpath/Download');
$telegram->setUploadPath('yourpath/Upload');
```

### Logging
Thrown Exceptions are not stored by default. You can Enable this feature adding this line in your 'webhook.php' or 'getUpdates.php'

```php
    Longman\TelegramBot\Logger::initialize('your_path/TelegramException.log');
```

Incoming update (json string from webhook and getUpdates) can be logged in a text file. Set those options with the methods:
```php
$telegram->setLogRequests(true);
$telegram->setLogPath($BOT_NAME . '.log');
```

Set verbosity to 3 to also log curl requests and responses from the bot to Telegram:

```php
$telegram->setLogRequests(true);
$telegram->setLogPath($BOT_NAME . '.log');
$telegram->setLogVerbosity(3);
```

## Documentation

Take a look at the repo [Wiki](https://github.com/akalongman/php-telegram-bot/wiki) for further information and tutorials!
Feel free to improve!

## Projects with this library

Here's a list of projects that feats this library, feel free to add yours!
- [Super-Dice-Roll](https://github.com/RafaelDelboni/Super-Dice-Roll) [@superdiceroll_bot](https://telegram.me/superdiceroll_bot)

## Troubleshooting

If you like living on the edge, please report any bugs you find on the
[PHP Telegram Bot issues](https://github.com/akalongman/php-telegram-bot/issues) page.

## Contributing

See [CONTRIBUTING](CONTRIBUTING.md) for more information.

## License

Please see the [LICENSE](LICENSE.md) included in this repository for a full copy of the MIT license,
which this project is licensed under.

## Credits

Credit list in [CREDITS](CREDITS)
