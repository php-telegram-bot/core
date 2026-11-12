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
        $migrated['botan_shortener'] = [
            'options'      => 'InnoDB|utf8mb4_unicode_520_ci',
            'columns'      => ['id' => 'bigint|NO|NULL|||'],
            'indexes'      => ['p:id'],
            'foreign_keys' => [],
        ];

        self::assertSame(['table botan_shortener'], array_keys(SchemaDiff::compare($this->snapshot(), $migrated)));
    }

    public function testTableOnlyInFreshSnapshotIsReported(): void
    {
        $fresh = $this->snapshot();
        $fresh['chat'] = [
            'options'      => 'InnoDB|utf8mb4_unicode_520_ci',
            'columns'      => ['id' => 'bigint|NO|NULL|||'],
            'indexes'      => ['p:id'],
            'foreign_keys' => [],
        ];

        self::assertSame(['table chat' => 'fresh only'], SchemaDiff::compare($fresh, $this->snapshot()));
    }

    public function testColumnIndexAndForeignKeyDifferences(): void
    {
        $migrated = $this->snapshot();
        $migrated['user']['columns']['username'] = 'char(255)|YES|NULL|utf8mb4|utf8mb4_unicode_520_ci|';
        $migrated['message']['columns']['new_chat_member'] = 'bigint|YES|NULL|||';
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
        $migrated['user']['indexes'] = ['p:id', '1:username'];
        $migrated['message']['indexes'][] = '0:user_id';
        $migrated['message']['foreign_keys'][1] = 'user_id>chat(id) ON DELETE RESTRICT ON UPDATE RESTRICT';

        self::assertSame(
            [
                'fk message(user_id)'    => 'fresh user(id) ON DELETE RESTRICT ON UPDATE RESTRICT; migrated chat(id) ON DELETE RESTRICT ON UPDATE RESTRICT',
                'index message(user_id)' => 'fresh non-unique; migrated non-unique + non-unique',
                'index user(username)'   => 'fresh non-unique; migrated unique',
            ],
            SchemaDiff::compare($this->snapshot(), $migrated)
        );
    }

    public function testColumnExtraIsCompared(): void
    {
        $fresh = $this->snapshot();
        $fresh['message']['columns']['id'] = 'bigint unsigned|NO|NULL|||auto_increment';

        self::assertSame(
            ['column message.id' => 'fresh bigint unsigned|NO|NULL|||auto_increment; migrated bigint unsigned|NO|NULL|||'],
            SchemaDiff::compare($fresh, $this->snapshot())
        );
    }

    public function testForeignKeyRulesAreCompared(): void
    {
        $migrated = $this->snapshot();
        $migrated['message']['foreign_keys'][1] = 'user_id>user(id) ON DELETE CASCADE ON UPDATE RESTRICT';

        self::assertSame(
            ['fk message(user_id)' => 'fresh user(id) ON DELETE RESTRICT ON UPDATE RESTRICT; migrated user(id) ON DELETE CASCADE ON UPDATE RESTRICT'],
            SchemaDiff::compare($this->snapshot(), $migrated)
        );
    }

    public function testPrimaryKeyIsToldApartFromUniqueIndex(): void
    {
        $migrated = $this->snapshot();
        $migrated['message']['indexes'][0] = '1:chat_id,id';

        self::assertSame(
            ['index message(chat_id,id)' => 'fresh primary; migrated unique'],
            SchemaDiff::compare($this->snapshot(), $migrated)
        );
    }

    public function testTableEngineAndCollationAreCompared(): void
    {
        $other_engine = $this->snapshot();
        $other_engine['message']['options'] = 'MyISAM|utf8mb4_unicode_520_ci';

        $other_collation = $this->snapshot();
        $other_collation['user']['options'] = 'InnoDB|utf8mb4_general_ci';

        self::assertSame(
            ['table message' => 'fresh InnoDB|utf8mb4_unicode_520_ci; migrated MyISAM|utf8mb4_unicode_520_ci'],
            SchemaDiff::compare($this->snapshot(), $other_engine)
        );
        self::assertSame(
            ['table user' => 'fresh InnoDB|utf8mb4_unicode_520_ci; migrated InnoDB|utf8mb4_general_ci'],
            SchemaDiff::compare($this->snapshot(), $other_collation)
        );
    }

    public function testEmptySnapshotIsAnError(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SchemaDiff::compare($this->snapshot(), []);
    }

    public function testEmptyFreshSnapshotIsAnError(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The fresh snapshot has no tables.');
        SchemaDiff::compare([], $this->snapshot());
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
     * @return array<string, array{options: string, columns: array<string, string>, indexes: array<int, string>, foreign_keys: array<int, string>}>
     */
    private function snapshot(): array
    {
        return [
            'user'    => [
                'options'      => 'InnoDB|utf8mb4_unicode_520_ci',
                'columns'      => [
                    'id'       => 'bigint|NO|NULL|||',
                    'username' => 'char(191)|YES|NULL|utf8mb4|utf8mb4_unicode_520_ci|',
                ],
                'indexes'      => ['p:id', '0:username'],
                'foreign_keys' => [],
            ],
            'message' => [
                'options'      => 'InnoDB|utf8mb4_unicode_520_ci',
                'columns'      => [
                    'chat_id' => 'bigint|NO|NULL|||',
                    'id'      => 'bigint unsigned|NO|NULL|||',
                    'user_id' => 'bigint|YES|NULL|||',
                ],
                'indexes'      => ['p:chat_id,id', '0:user_id'],
                'foreign_keys' => [
                    'chat_id>chat(id) ON DELETE RESTRICT ON UPDATE RESTRICT',
                    'user_id>user(id) ON DELETE RESTRICT ON UPDATE RESTRICT',
                ],
            ],
        ];
    }
}
