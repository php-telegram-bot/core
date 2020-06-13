<?php

/**
 * Import all updates from a raw updates log file into the database.
 * Works for both webhook and getUpdates.
 *
 * Modify $updates_log_file_path and $mysql_credentials below!
 *
 * Requires PHP7+
 *
 * @todo Move to dedicated CLI tool.
 */

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

require __DIR__ . '/../vendor/autoload.php';

// This is the file that contains the raw updates.
$updates_log_file_path = __DIR__ . '/updates.log';

// Credentials of the database to import updates to.
$mysql_credentials = [
    'host'     => 'localhost',
    'port'     => 3306, // optional
    'user'     => 'dbuser',
    'password' => 'dbpass',
    'database' => 'dbname',
];

try {
    // Create dummy Telegram API object and connect to MySQL database.
    (new Telegram('1:A'))->enableMySql($mysql_credentials);

    // Load the updates log file to iterate over.
    $updates_log_file = new SplFileObject($updates_log_file_path);
    $updates_log_file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);

    foreach ($updates_log_file as $update_json) {
        if ($update_arr = json_decode($update_json, true)) {
            echo $update_json . PHP_EOL;

            // Get all updates on this line.
            $updates_data = array_filter($update_arr['result'] ?? [$update_arr]);
            foreach ($updates_data as $update_data) {
                $update = new Update($update_data);
                printf(
                    'Update ID %d %s' . PHP_EOL,
                    $update->getUpdateId(),
                    DB::insertRequest($update) ? '(success)' : '(failed) ' . implode(' ', DB::getPdo()->errorInfo())
                );
            }
        }
    }
} catch (Throwable $e) {
    // Output any errors.
    echo $e;
}
