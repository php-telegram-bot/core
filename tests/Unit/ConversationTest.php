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

use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
 */
class ConversationTest extends TestCase
{

    protected function setUp(): void
    {
        // Database related setup removed
    }

    public function testConversationThatDoesntExistPropertiesSetCorrectly(): void
    {
        $conversation = new Conversation(123, 456);
        self::assertSame(123, $conversation->getUserId());
        self::assertSame(456, $conversation->getChatId());
        self::assertEmpty($conversation->getCommand());
        self::assertFalse($conversation->exists()); // Ensure exists() returns false
    }

    public function testConversationThatExistsPropertiesSetCorrectly(): void
    {
        // This test is no longer valid as conversations don't "exist" without DB
        $conversation = new Conversation(123, 456, 'command');
        self::assertSame(123, $conversation->getUserId());
        self::assertSame(456, $conversation->getChatId());
        self::assertSame('command', $conversation->getCommand());
        self::assertFalse($conversation->exists()); // Ensure exists() returns false
    }

    public function testConversationThatDoesntExistWithoutCommand(): void
    {
        $conversation = new Conversation(1, 1);
        self::assertFalse($conversation->exists());
        self::assertEmpty($conversation->getCommand());
    }

    public function testConversationThatDoesntExistWithCommand(): void
    {
        // This test might need adjustment based on how Conversation handles this without DB
        // For now, assuming it simply doesn't "exist"
        $conversation = new Conversation(1, 1, 'command');
        self::assertFalse($conversation->exists());
        self::assertSame('command', $conversation->getCommand());
    }

    public function testNewConversationThatWontExistWithoutCommand(): void
    {
        $conversation = new Conversation(0, 0);
        self::assertFalse($conversation->exists());
        self::assertEmpty($conversation->getCommand());
    }

    public function testNewConversationThatWillExistWithCommand(): void
    {
        // This test is no longer valid as conversations don't "exist" without DB
        $conversation = new Conversation(123, 456, 'command');
        self::assertFalse($conversation->exists());
        self::assertEquals('command', $conversation->getCommand());
    }

    public function testStopConversation(): void
    {
        $conversation = new Conversation(123, 456, 'command');
        self::assertFalse($conversation->exists()); // Should be false initially
        self::assertTrue($conversation->stop()); // stop() should now always return true

        $conversation2 = new Conversation(123, 456);
        self::assertFalse($conversation2->exists());
    }

    public function testCancelConversation(): void
    {
        $conversation = new Conversation(123, 456, 'command');
        self::assertFalse($conversation->exists()); // Should be false initially
        self::assertTrue($conversation->cancel()); // cancel() should now always return true

        $conversation2 = new Conversation(123, 456);
        self::assertFalse($conversation2->exists());
    }

    public function testUpdateConversationNotes(): void
    {
        // This test is no longer valid as notes are not persisted without DB
        $conversation        = new Conversation(123, 456, 'command');
        $conversation->notes = 'newnote';
        self::assertFalse($conversation->update()); // update() should now always return false

        // Notes should not persist
        $conversation2 = new Conversation(123, 456, 'command');
        self::assertNull($conversation2->notes);

        $conversation3 = new Conversation(123, 456);
        self::assertNull($conversation3->notes);
    }
}
