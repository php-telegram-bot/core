<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Utils;

use InvalidArgumentException;

/**
 * Compare two database schema snapshots, a fresh one and a migrated one.
 *
 * A snapshot maps each table name to its columns, indexes and foreign keys:
 *
 *     'user' => [
 *         'columns'      => ['username' => 'char(191)|YES|NULL|utf8mb4|utf8mb4_unicode_520_ci'],
 *         'indexes'      => ['1:id', '0:username'],
 *         'foreign_keys' => ['chat_id>chat(id)'],
 *     ]
 *
 * A column is "<type>|<nullable>|<default>|<charset>|<collation>", an index is
 * "<unique 0/1>:<col,col>" and a foreign key is "<col,col>><ref_table>(<col,col>)".
 * Index and foreign key names are not part of a snapshot, so generated names never differ.
 *
 * Each difference is keyed "table <t>", "column <t>.<c>", "index <t>(<cols>)" or
 * "fk <t>(<cols>)", the keys listed in the accepted differences file.
 */
class SchemaDiff
{
    /**
     * Compare two snapshots and return every difference, sorted by key.
     *
     * A table present on one side only yields its "table" key and nothing for its columns.
     * Indexes and foreign keys are grouped by their columns: two indexes over the same
     * columns are compared as a list with repeats, and a uniqueness change or a changed
     * reference is reported under the key of those columns.
     *
     * @param array<string, array{columns: array<string, string>, indexes: array<int, string>, foreign_keys: array<int, string>}> $fresh
     * @param array<string, array{columns: array<string, string>, indexes: array<int, string>, foreign_keys: array<int, string>}> $migrated
     *
     * @return array<string, string> Difference key to human-readable detail
     * @throws InvalidArgumentException When either snapshot has no tables
     */
    public static function compare(array $fresh, array $migrated): array
    {
        if ($fresh === []) {
            throw new InvalidArgumentException('The fresh snapshot has no tables.');
        }

        if ($migrated === []) {
            throw new InvalidArgumentException('The migrated snapshot has no tables.');
        }

        $differences = [];

        foreach (array_diff_key($fresh, $migrated) as $table => $definition) {
            $differences['table ' . $table] = 'fresh only';
        }

        foreach (array_diff_key($migrated, $fresh) as $table => $definition) {
            $differences['table ' . $table] = 'migrated only';
        }

        foreach (array_intersect_key($fresh, $migrated) as $table => $definition) {
            $differences += self::compareColumns($table, $definition['columns'], $migrated[$table]['columns']);
            $differences += self::compareGroups(
                'index ' . $table,
                self::groupIndexes($definition['indexes']),
                self::groupIndexes($migrated[$table]['indexes'])
            );
            $differences += self::compareGroups(
                'fk ' . $table,
                self::groupForeignKeys($definition['foreign_keys']),
                self::groupForeignKeys($migrated[$table]['foreign_keys'])
            );
        }

        ksort($differences, SORT_STRING);

        return $differences;
    }

    /**
     * Read the difference keys from the contents of the accepted differences file.
     *
     * Blank lines and lines starting with "#" are skipped; surrounding whitespace is trimmed.
     *
     * @param string $contents
     *
     * @return array<int, string>
     */
    public static function parseAccepted(string $contents): array
    {
        $keys = [];

        foreach (preg_split('/\R/', $contents) as $line) {
            $line = trim($line);

            if ($line === '' || $line[0] === '#') {
                continue;
            }

            $keys[] = $line;
        }

        return $keys;
    }

    /**
     * Split differences into the ones not accepted and the accepted keys that match no difference.
     *
     * @param array<string, string> $differences
     * @param array<int, string>    $accepted
     *
     * @return array{unexpected: array<string, string>, stale: array<int, string>}
     */
    public static function filter(array $differences, array $accepted): array
    {
        $stale = array_filter($accepted, static function (string $key) use ($differences): bool {
            return !array_key_exists($key, $differences);
        });

        return [
            'unexpected' => array_diff_key($differences, array_flip($accepted)),
            'stale'      => array_values($stale),
        ];
    }

    /**
     * Compare the columns of one table present on both sides.
     *
     * @param string                $table
     * @param array<string, string> $fresh
     * @param array<string, string> $migrated
     *
     * @return array<string, string>
     */
    private static function compareColumns(string $table, array $fresh, array $migrated): array
    {
        $differences = [];

        foreach (array_keys($fresh + $migrated) as $column) {
            $fresh_definition    = $fresh[$column] ?? null;
            $migrated_definition = $migrated[$column] ?? null;

            if ($fresh_definition === $migrated_definition) {
                continue;
            }

            $differences['column ' . $table . '.' . $column] = self::describe(
                $fresh_definition === null ? [] : [$fresh_definition],
                $migrated_definition === null ? [] : [$migrated_definition]
            );
        }

        return $differences;
    }

    /**
     * Compare two groupings (columns to sorted list of values) and key each mismatch by its columns.
     *
     * @param string                            $prefix   "index <t>" or "fk <t>"
     * @param array<string, array<int, string>> $fresh
     * @param array<string, array<int, string>> $migrated
     *
     * @return array<string, string>
     */
    private static function compareGroups(string $prefix, array $fresh, array $migrated): array
    {
        $differences = [];

        foreach (array_keys($fresh + $migrated) as $columns) {
            $fresh_values    = $fresh[$columns] ?? [];
            $migrated_values = $migrated[$columns] ?? [];

            if ($fresh_values === $migrated_values) {
                continue;
            }

            $differences[$prefix . '(' . $columns . ')'] = self::describe($fresh_values, $migrated_values);
        }

        return $differences;
    }

    /**
     * Group index entries by their columns, each group a sorted list of "unique" and "non-unique".
     *
     * @param array<int, string> $indexes
     *
     * @return array<string, array<int, string>>
     */
    private static function groupIndexes(array $indexes): array
    {
        $groups = [];

        foreach ($indexes as $index) {
            [$unique, $columns] = explode(':', $index, 2);

            $groups[$columns][] = $unique === '1' ? 'unique' : 'non-unique';
        }

        return self::sortGroups($groups);
    }

    /**
     * Group foreign key entries by their columns, each group a sorted list of references.
     *
     * @param array<int, string> $foreign_keys
     *
     * @return array<string, array<int, string>>
     */
    private static function groupForeignKeys(array $foreign_keys): array
    {
        $groups = [];

        foreach ($foreign_keys as $foreign_key) {
            [$columns, $reference] = explode('>', $foreign_key, 2);

            $groups[$columns][] = $reference;
        }

        return self::sortGroups($groups);
    }

    /**
     * Sort the values inside each group, so the order the server listed them in does not matter.
     *
     * @param array<string, array<int, string>> $groups
     *
     * @return array<string, array<int, string>>
     */
    private static function sortGroups(array $groups): array
    {
        foreach ($groups as $columns => $values) {
            sort($values, SORT_STRING);

            $groups[$columns] = $values;
        }

        return $groups;
    }

    /**
     * Describe both sides of a difference, "absent" for a side that has nothing.
     *
     * @param array<int, string> $fresh
     * @param array<int, string> $migrated
     *
     * @return string
     */
    private static function describe(array $fresh, array $migrated): string
    {
        $fresh_text    = $fresh === [] ? 'absent' : implode(' + ', $fresh);
        $migrated_text = $migrated === [] ? 'absent' : implode(' + ', $migrated);

        return 'fresh ' . $fresh_text . '; migrated ' . $migrated_text;
    }
}
