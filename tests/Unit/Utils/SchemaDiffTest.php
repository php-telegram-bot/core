<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit\Utils;

use InvalidArgumentException;
use Longman\TelegramBot\Tests\Unit\TestCase;
use Longman\TelegramBot\Utils\SchemaDiff;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
 */
class SchemaDiffTest extends TestCase
{
    public function testIdenticalSnapshotsHaveNoDifferences(): void
    {
        self::assertSame([], SchemaDiff::compare($this->snapshot(), $this->snapshot()));
    }

    public function testTableOnOneSideYieldsOnlyTheTableKey(): void
    {
        $migrated = $this->snapshot();
        $migrated['botan_shortener'] = ['columns' => ['id' => 'bigint|NO||'], 'indexes' => ['1:id'], 'foreign_keys' => []];

        self::assertSame(['table botan_shortener'], array_keys(SchemaDiff::compare($this->snapshot(), $migrated)));
    }

    public function testColumnIndexAndForeignKeyDifferences(): void
    {
        $migrated = $this->snapshot();
        $migrated['user']['columns']['username'] = 'char(255)|YES||utf8mb4|utf8mb4_unicode_520_ci';
        $migrated['message']['columns']['new_chat_member'] = 'bigint|YES|||';
        $migrated['message']['indexes'][] = '0:new_chat_member';
        unset($migrated['message']['foreign_keys'][0]);

        self::assertSame(
            ['column message.new_chat_member', 'column user.username', 'fk message(chat_id)', 'index message(new_chat_member)'],
            array_keys(SchemaDiff::compare($this->snapshot(), $migrated))
        );
    }

    public function testUniquenessRepeatsAndReferencesAreComparedPerColumns(): void
    {
        $migrated = $this->snapshot();
        $migrated['user']['indexes'] = ['1:id', '1:username'];
        $migrated['message']['indexes'][] = '0:user_id';
        $migrated['message']['foreign_keys'][1] = 'user_id>chat(id)';

        self::assertSame(
            [
                'fk message(user_id)'    => 'fresh user(id); migrated chat(id)',
                'index message(user_id)' => 'fresh non-unique; migrated non-unique + non-unique',
                'index user(username)'   => 'fresh non-unique; migrated unique',
            ],
            SchemaDiff::compare($this->snapshot(), $migrated)
        );
    }

    public function testEmptySnapshotIsAnError(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SchemaDiff::compare($this->snapshot(), []);
    }

    public function testFilterSeparatesUnexpectedAndStale(): void
    {
        $accepted = SchemaDiff::parseAccepted("# unused, kept on purpose\ntable botan_shortener\n\ncolumn user.username\n");
        $result   = SchemaDiff::filter(['table botan_shortener' => 'x', 'column chat.title' => 'y'], $accepted);

        self::assertSame(['column chat.title' => 'y'], $result['unexpected']);
        self::assertSame(['column user.username'], $result['stale']);
    }

    /**
     * Two-table snapshot in the shape the CLI reads from information_schema.
     *
     * @return array<string, array{columns: array<string, string>, indexes: array<int, string>, foreign_keys: array<int, string>}>
     */
    private function snapshot(): array
    {
        return [
            'user'    => [
                'columns'      => [
                    'id'       => 'bigint|NO|||',
                    'username' => 'char(191)|YES||utf8mb4|utf8mb4_unicode_520_ci',
                ],
                'indexes'      => ['1:id', '0:username'],
                'foreign_keys' => [],
            ],
            'message' => [
                'columns'      => [
                    'chat_id' => 'bigint|NO|||',
                    'id'      => 'bigint unsigned|NO|||',
                    'user_id' => 'bigint|YES|||',
                ],
                'indexes'      => ['1:chat_id,id', '0:user_id'],
                'foreign_keys' => ['chat_id>chat(id)', 'user_id>user(id)'],
            ],
        ];
    }
}
