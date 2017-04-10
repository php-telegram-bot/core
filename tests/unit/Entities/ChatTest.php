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

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class ChatTest extends TestCase
{
    public function testChatType()
    {
        $chat = TestHelpers::getFakeChatObject();
        self::assertEquals('private', $chat->getType());

        $chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => null]);
        self::assertEquals('group', $chat->getType());

        $chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => 'supergroup']);
        self::assertEquals('supergroup', $chat->getType());

        $chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => 'channel']);
        self::assertEquals('channel', $chat->getType());
    }

    public function testIsChatType()
    {
        $chat = TestHelpers::getFakeChatObject();
        self::assertTrue($chat->isPrivateChat());

        $chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => null]);
        self::assertTrue($chat->isGroupChat());

        $chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => 'supergroup']);
        self::assertTrue($chat->isSuperGroup());

        $chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => 'channel']);
        self::assertTrue($chat->isChannel());
    }

    public function testTryMention()
    {
        // Username.
        $chat = TestHelpers::getFakeChatObject(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => 'jtaylor']);
        self::assertEquals('@jtaylor', $chat->tryMention());

        // First name.
        $chat = TestHelpers::getFakeChatObject(['id' => 1, 'first_name' => 'John', 'last_name' => null, 'username' => null]);
        self::assertEquals('John', $chat->tryMention());

        // First and Last name.
        $chat = TestHelpers::getFakeChatObject(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => null]);
        self::assertEquals('John Taylor', $chat->tryMention());

        // Non-private chat should return title.
        $chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => null, 'title' => 'My group chat']);
        self::assertSame('My group chat', $chat->tryMention());
    }
}
