# Commands

The command system is the core of your bot's functionality. The library makes it easy to create and manage commands that your bot will respond to.

## How Commands Work

When your bot receives a message that starts with a `/`, it interprets it as a command. For example, `/start` or `/help`. The library automatically matches the command name to a corresponding command class that you have defined.

There are three types of commands:

-   **User Commands:** Standard commands that any user can execute.
-   **Admin Commands:** Commands that can only be executed by a bot admin.
-   **System Commands:** Internal commands that handle non-command updates, like new users joining a chat (`new_chat_members`) or inline queries. You generally don't need to create these unless you want to override default behavior.

## Creating a Custom Command

Let's create a simple command that replies with "Hello, World!".

1.  **Create a commands directory:** It's good practice to store your custom commands in a dedicated directory.
    ```bash
    mkdir MyCommands
    ```

2.  **Create the command file:** Inside `MyCommands`, create a new file named `HelloWorldCommand.php`.

    ```php
    <?php

    namespace Longman\TelegramBot\Commands\UserCommands;

    use Longman\TelegramBot\Commands\UserCommand;
    use Longman\TelegramBot\Entities\ServerResponse;
    use Longman\TelegramBot\Exception\TelegramException;

    class HelloWorldCommand extends UserCommand
    {
        /**
         * @var string
         */
        protected $name = 'helloworld';

        /**
         * @var string
         */
        protected $description = 'A simple command that replies with Hello, World!';

        /**
         * @var string
         */
        protected $usage = '/helloworld';

        /**
         * @var string
         */
        protected $version = '1.0.0';

        /**
         * Main command execution
         *
         * @return ServerResponse
         * @throws TelegramException
         */
        public function execute(): ServerResponse
        {
            $message = $this->getMessage();
            $chat_id = $message->getChat()->getId();

            $data = [
                'chat_id' => $chat_id,
                'text'    => 'Hello, World!',
            ];

            return \Longman\TelegramBot\Request::sendMessage($data);
        }
    }
    ```

### Key Properties of a Command:

-   `$name`: The command name without the `/` (e.g., `start`, `help`, `helloworld`).
-   `$description`: A short description, used by the `/help` command.
-   `$usage`: How to use the command (e.g., `/weather <city>`).
-   `$version`: The version of your command.
-   `execute()`: The main method that runs when the command is called.

## Registering Your Commands

For the library to find your new command, you must tell it where to look. You do this by adding the path to your commands directory.

In your main bot file (`getUpdates.php` or `hook.php`), add the following line after instantiating the `Telegram` object:

```php
//...
$telegram = new Longman\TelegramBot\Telegram($api_key, $bot_username);

// Add your custom commands path
$telegram->addCommandsPath(__DIR__ . '/MyCommands');

//...
```

Now, your bot will automatically load and execute your `HelloWorldCommand` when a user sends `/helloworld`.

## Admin Commands

To create a command that only admins can use, simply extend `AdminCommand` instead of `UserCommand`.

```php
<?php
namespace Longman\TelegramBot\Commands\AdminCommands;

use Longman\TelegramBot\Commands\AdminCommand;
// ...

class MyAdminCommand extends AdminCommand
{
    // ...
}
```

You also need to specify who the admins are. You can do this with `enableAdmin()` or `enableAdmins()`:

```php
//...
$telegram = new Longman\TelegramBot\Telegram($api_key, $bot_username);

// Set a single admin
$telegram->enableAdmin(123456789); // Replace with your Telegram User ID

// Or set multiple admins
$telegram->enableAdmins([123456789, 987654321]);

//...
```

## Command Configuration

You can pass specific configuration options to your commands using `setCommandConfig()`. For example, if your command needs an API key:

```php
$telegram->setCommandConfig('mycommand', ['api_key' => 'SOME_API_KEY']);
```

Inside your command, you can access this config like this:

```php
$api_key = $this->getConfig('api_key');
```
