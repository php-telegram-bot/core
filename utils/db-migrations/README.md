# Database migrations using [Phinx]

## For users

### Run the migrations

You'll need to create a new file where the database connection is initialised, to allow the migration to do its thing.

Minimal example `db-migration.php` in the root of your project:
```php
<?php

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Telegram;

// Load composer
require __DIR__ . '/vendor/autoload.php';

// Enter your MySQL database credentials
$mysql_credentials = [
   'host'     => 'localhost',
   'port'     => 3306, // optional
   'user'     => 'dbuser',
   'password' => 'dbpass',
   'database' => 'dbname',
];

try {
    // Create Telegram API object (with dummy API key)
    $telegram = new Telegram('1:a');
    
    // Initialise the database connection.
    $telegram->enableMySql($mysql_credentials);

    // Run the migration!
    DB::runMigrations();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

This script can now be called to perform migrations and rollbacks:
```bash
# Migrate
$ php db-migration.php

# Rollback
$ php db-migration.php rollback
```

## For developers

### Create new migration

- `$ vendor/bin/phinx create MyMigrationName`
- Edit new migration template.
- User can now call DB migration script.

[Phinx]: https://github.com/cakephp/phinx
