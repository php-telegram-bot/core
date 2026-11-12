<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Compare a fresh and a migrated database on one server through information_schema.
 *
 * Usage: php utils/db-schema-diff.php <fresh_db> <migrated_db>
 *
 * The connection comes from DB_HOST (default 127.0.0.1), DB_PORT (3306), DB_USER (root)
 * and DB_PASSWORD, always over TCP: DB_HOST localhost is reached at 127.0.0.1, as
 * db-prepare.sh does. Differences listed in db-schema-diff-accepted.txt are accepted.
 * Prints one "unexpected: <key>: <detail>" line per other difference and one
 * "stale: <key>" line per accepted key that matches no difference.
 *
 * Exit status: 0 when nothing is unexpected or stale, 1 when a difference is not accepted
 * or an accepted key matches no difference, 2 on a usage or connection error and on a
 * missing or empty database.
 */

use Longman\TelegramBot\Utils\SchemaDiff;

require __DIR__ . '/../vendor/autoload.php';

if ($argc !== 3) {
    fwrite(STDERR, 'Usage: php utils/db-schema-diff.php <fresh_db> <migrated_db>' . PHP_EOL);
    exit(2);
}

[, $fresh_db, $migrated_db] = $argv;

$host = getenv('DB_HOST') ?: '127.0.0.1';
// PDO turns the host localhost into a Unix socket connection that ignores the port.
$host = $host === 'localhost' ? '127.0.0.1' : $host;
$port = getenv('DB_PORT') ?: '3306';
$user = getenv('DB_USER') ?: 'root';

$accepted_file = __DIR__ . '/db-schema-diff-accepted.txt';
if (!is_readable($accepted_file)) {
    fwrite(STDERR, 'Cannot read ' . $accepted_file . PHP_EOL);
    exit(2);
}

try {
    $pdo = new PDO(
        'mysql:host=' . $host . ';port=' . $port . ';charset=utf8mb4',
        $user,
        (string) getenv('DB_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    fwrite(STDERR, 'Cannot connect to ' . $host . ':' . $port . ' as ' . $user . ': ' . $e->getMessage() . PHP_EOL);
    exit(2);
}

/**
 * Read the schema snapshot of one database, in the shape SchemaDiff::compare() takes.
 * A missing database yields an empty snapshot.
 *
 * @param PDO    $pdo
 * @param string $schema
 *
 * @return array<string, array{options: string, columns: array<string, string>, indexes: array<int, string>, foreign_keys: array<int, string>}>
 */
$read_snapshot = static function (PDO $pdo, string $schema): array {
    $query = static function (string $sql) use ($pdo, $schema): array {
        $statement = $pdo->prepare($sql);
        $statement->execute([$schema]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    };

    $snapshot = [];

    $tables = $query(
        "SELECT TABLE_NAME AS table_name, ENGINE AS engine, TABLE_COLLATION AS table_collation
         FROM information_schema.TABLES
         WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'"
    );
    foreach ($tables as $row) {
        $snapshot[$row['table_name']] = [
            'options'      => (string) $row['engine'] . '|' . (string) $row['table_collation'],
            'columns'      => [],
            'indexes'      => [],
            'foreign_keys' => [],
        ];
    }

    $columns = $query(
        'SELECT TABLE_NAME AS table_name, COLUMN_NAME AS column_name, COLUMN_TYPE AS column_type, IS_NULLABLE AS is_nullable,
                COLUMN_DEFAULT AS column_default, CHARACTER_SET_NAME AS character_set_name, COLLATION_NAME AS collation_name,
                EXTRA AS extra
         FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = ?'
    );
    foreach ($columns as $row) {
        if (!isset($snapshot[$row['table_name']])) {
            continue;
        }

        // An SQL NULL default is written as NULL, so that on MySQL, which reports a string
        // default unquoted, DEFAULT '' (an empty field) stays distinct from DEFAULT NULL.
        $snapshot[$row['table_name']]['columns'][$row['column_name']] = implode('|', [
            $row['column_type'],
            $row['is_nullable'],
            $row['column_default'] ?? 'NULL',
            (string) $row['character_set_name'],
            (string) $row['collation_name'],
            (string) $row['extra'],
        ]);
    }

    $indexes = [];
    $statistics = $query(
        'SELECT TABLE_NAME AS table_name, INDEX_NAME AS index_name, NON_UNIQUE AS non_unique, COLUMN_NAME AS column_name
         FROM information_schema.STATISTICS
         WHERE TABLE_SCHEMA = ?
         ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX'
    );
    foreach ($statistics as $row) {
        $kind = (int) $row['non_unique'] === 0 ? '1' : '0';
        if ($row['index_name'] === 'PRIMARY') {
            $kind = 'p';
        }

        $indexes[$row['table_name']][$row['index_name']]['kind'] = $kind;
        $indexes[$row['table_name']][$row['index_name']]['columns'][] = (string) $row['column_name'];
    }

    foreach ($indexes as $table => $table_indexes) {
        if (!isset($snapshot[$table])) {
            continue;
        }

        foreach ($table_indexes as $index) {
            $snapshot[$table]['indexes'][] = $index['kind'] . ':' . implode(',', $index['columns']);
        }
    }

    $foreign_keys = [];
    $key_columns = $query(
        'SELECT k.TABLE_NAME AS table_name, k.CONSTRAINT_NAME AS constraint_name, k.COLUMN_NAME AS column_name,
                k.REFERENCED_TABLE_NAME AS referenced_table_name, k.REFERENCED_COLUMN_NAME AS referenced_column_name,
                r.DELETE_RULE AS delete_rule, r.UPDATE_RULE AS update_rule
         FROM information_schema.KEY_COLUMN_USAGE k
         JOIN information_schema.REFERENTIAL_CONSTRAINTS r
           ON r.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA AND r.TABLE_NAME = k.TABLE_NAME AND r.CONSTRAINT_NAME = k.CONSTRAINT_NAME
         WHERE k.TABLE_SCHEMA = ? AND k.REFERENCED_TABLE_NAME IS NOT NULL
         ORDER BY k.TABLE_NAME, k.CONSTRAINT_NAME, k.ORDINAL_POSITION'
    );
    foreach ($key_columns as $row) {
        $foreign_keys[$row['table_name']][$row['constraint_name']]['referenced_table'] = $row['referenced_table_name'];
        $foreign_keys[$row['table_name']][$row['constraint_name']]['rules'] = 'ON DELETE ' . $row['delete_rule'] . ' ON UPDATE ' . $row['update_rule'];
        $foreign_keys[$row['table_name']][$row['constraint_name']]['columns'][] = $row['column_name'];
        $foreign_keys[$row['table_name']][$row['constraint_name']]['referenced_columns'][] = $row['referenced_column_name'];
    }

    foreach ($foreign_keys as $table => $table_foreign_keys) {
        if (!isset($snapshot[$table])) {
            continue;
        }

        foreach ($table_foreign_keys as $foreign_key) {
            $snapshot[$table]['foreign_keys'][] = implode(',', $foreign_key['columns'])
                . '>' . $foreign_key['referenced_table']
                . '(' . implode(',', $foreign_key['referenced_columns']) . ') '
                . $foreign_key['rules'];
        }
    }

    return $snapshot;
};

try {
    $differences = SchemaDiff::compare($read_snapshot($pdo, $fresh_db), $read_snapshot($pdo, $migrated_db));
} catch (InvalidArgumentException $e) {
    fwrite(
        STDERR,
        $e->getMessage() . ' A missing or empty database is an error (fresh ' . $fresh_db . ', migrated ' . $migrated_db . ').' . PHP_EOL
    );
    exit(2);
} catch (PDOException $e) {
    fwrite(STDERR, 'Cannot read the schema: ' . $e->getMessage() . PHP_EOL);
    exit(2);
}

$result = SchemaDiff::filter($differences, SchemaDiff::parseAccepted((string) file_get_contents($accepted_file)));

foreach ($result['unexpected'] as $key => $detail) {
    echo 'unexpected: ' . $key . ': ' . $detail . PHP_EOL;
}

foreach ($result['stale'] as $key) {
    echo 'stale: ' . $key . PHP_EOL;
}

exit($result['unexpected'] === [] && $result['stale'] === [] ? 0 : 1);
