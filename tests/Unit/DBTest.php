<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit;

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @package         TelegramTest
 */
class DBTest extends TestCase
{
    protected function setUp(): void
    {
        $credentials = [
            'host'     => PHPUNIT_DB_HOST,
            'port'     => PHPUNIT_DB_PORT,
            'database' => PHPUNIT_DB_NAME,
            'user'     => PHPUNIT_DB_USER,
            'password' => PHPUNIT_DB_PASS,
        ];

        $telegram = new Telegram(self::$dummy_api_key, 'testbot');
        $telegram->enableMySql($credentials);

        TestHelpers::emptyDb($credentials);
    }

    public function testInsertMessageReactionCountUpdateForChannelPost(): void
    {
        $update = new Update([
            'update_id'              => 1001,
            'message_reaction_count' => [
                'chat'       => ['id' => -1001234567890, 'type' => 'channel', 'title' => 'Channel'],
                'message_id' => 42,
                'date'       => time(),
                'reactions'  => [
                    ['type' => ['type' => 'emoji', 'emoji' => '👍'], 'total_count' => 3],
                ],
            ],
        ], 'testbot');

        self::assertTrue(DB::insertRequest($update));

        $row = DB::getPdo()->query('SELECT `chat_id`, `message_id`, `reactions` FROM `message_reaction_count`')->fetch();
        self::assertEquals(-1001234567890, $row['chat_id']);
        self::assertEquals(42, $row['message_id']);
        self::assertSame(
            [['type' => ['type' => 'emoji', 'emoji' => '👍'], 'total_count' => 3]],
            json_decode($row['reactions'], true)
        );

        $update_row = DB::getPdo()->query('SELECT `chat_id`, `message_reaction_count_id` FROM `telegram_update` WHERE `id` = 1001')->fetch();
        self::assertNotFalse($update_row, 'The update itself must be stored even when the message is unknown');
        self::assertEquals(-1001234567890, $update_row['chat_id']);
        self::assertNotNull($update_row['message_reaction_count_id']);
    }

    public function testInsertMessageReactionUpdateForUnknownMessage(): void
    {
        $update = new Update([
            'update_id'        => 1003,
            'message_reaction' => [
                'chat'         => ['id' => 222, 'type' => 'private', 'first_name' => 'First'],
                'message_id'   => 44,
                'user'         => ['id' => 111, 'is_bot' => false, 'first_name' => 'First'],
                'date'         => time(),
                'old_reaction' => [],
                'new_reaction' => [['type' => 'emoji', 'emoji' => '👍']],
            ],
        ], 'testbot');

        self::assertTrue(DB::insertRequest($update));

        $update_row = DB::getPdo()->query('SELECT `chat_id`, `message_reaction_id` FROM `telegram_update` WHERE `id` = 1003')->fetch();
        self::assertNotFalse($update_row, 'The update itself must be stored even when the message is unknown');
        self::assertEquals(222, $update_row['chat_id']);
        self::assertNotNull($update_row['message_reaction_id']);
    }

    public function testInsertCallbackQueryWithInaccessibleMessage(): void
    {
        $update = new Update([
            'update_id'      => 1002,
            'callback_query' => [
                'id'            => '5946640685727949871',
                'from'          => ['id' => 111, 'is_bot' => false, 'first_name' => 'First'],
                'message'       => [
                    'chat'       => ['id' => 222, 'type' => 'private', 'first_name' => 'First'],
                    'message_id' => 43,
                    'date'       => 0,
                ],
                'chat_instance' => '123',
                'data'          => 'data',
            ],
        ], 'testbot');

        self::assertTrue(DB::insertRequest($update));

        $row = DB::getPdo()->query('SELECT `user_id`, `chat_id`, `message_id` FROM `callback_query`')->fetch();
        self::assertEquals(111, $row['user_id']);
        self::assertEquals(222, $row['chat_id']);
        self::assertNull($row['message_id']);
    }

    /**
     * @dataProvider forgedCallbackQueryIdsProvider
     *
     * @param int|string $message_id
     * @param int|string $chat_id
     */
    public function testInsertCallbackQueryDoesNotRunForgedIdsAsSql($message_id, $chat_id): void
    {
        $update = new Update([
            'update_id'      => 1004,
            'callback_query' => [
                'id'            => '5946640685727949872',
                'from'          => ['id' => 111, 'is_bot' => false, 'first_name' => 'First'],
                'message'       => [
                    'chat'       => ['id' => $chat_id, 'type' => 'private', 'first_name' => 'First'],
                    'message_id' => $message_id,
                    'date'       => 0,
                ],
                'chat_instance' => '123',
                'data'          => 'data',
            ],
        ], 'testbot');

        DB::insertRequest($update);

        $first_name = DB::getPdo()->query('SELECT `first_name` FROM `user` WHERE `id` = 111')->fetchColumn();
        self::assertSame('First', $first_name, 'An id taken from the update must not run as SQL');
    }

    /**
     * A webhook request can carry any JSON value where Telegram sends an integer.
     *
     * @return array<string, array{0: int|string, 1: int|string}>
     */
    public function forgedCallbackQueryIdsProvider(): array
    {
        $statement = "UPDATE `user` SET `first_name` = 'injected' WHERE `id` = 111";

        return [
            'message id' => ['0; ' . $statement . '; SELECT 1 FROM DUAL WHERE 1 = 1', 222],
            'chat id'    => [43, '222; ' . $statement],
        ];
    }
}
