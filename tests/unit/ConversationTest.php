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
use Longman\TelegramBot\Telegram;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class ConversationTest extends TestCase
{
    /**
     * @var \Longman\TelegramBot\Telegram
     */
    private $telegram;

    protected function setUp()
    {
        $credentials = [
            'host'     => PHPUNIT_DB_HOST,
            'database' => PHPUNIT_DB_NAME,
            'user'     => PHPUNIT_DB_USER,
            'password' => PHPUNIT_DB_PASS,
        ];

        $this->telegram = new Telegram(self::$dummy_api_key, 'testbot');
        $this->telegram->enableMySql($credentials);

        //Make sure we start with an empty DB for each test.
        TestHelpers::emptyDb($credentials);
    }

    public function testConversationThatDoesntExistPropertiesSetCorrectly()
    {
        $conversation = new Conversation(123, 456);
        $this->assertAttributeEquals(123, 'user_id', $conversation);
        $this->assertAttributeEquals(456, 'chat_id', $conversation);
        $this->assertAttributeEquals(null, 'command', $conversation);
    }

    public function testConversationThatExistsPropertiesSetCorrectly()
    {
        $info = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertAttributeEquals($info['user_id'], 'user_id', $conversation);
        $this->assertAttributeEquals($info['chat_id'], 'chat_id', $conversation);
        $this->assertAttributeEquals('command', 'command', $conversation);
    }

    public function testConversationThatDoesntExistWithoutCommand()
    {
        $conversation = new Conversation(1, 1);
        $this->assertFalse($conversation->exists());
        $this->assertNull($conversation->getCommand());
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     */
    public function testConversationThatDoesntExistWithCommand()
    {
        new Conversation(1, 1, 'command');
    }

    public function testNewConversationThatWontExistWithoutCommand()
    {
        TestHelpers::startFakeConversation();
        $conversation = new Conversation(0, 0);
        $this->assertFalse($conversation->exists());
        $this->assertNull($conversation->getCommand());
    }

    public function testNewConversationThatWillExistWithCommand()
    {
        $info = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $this->assertEquals('command', $conversation->getCommand());
    }

    public function testStopConversation()
    {
        $info = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $conversation->stop();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertFalse($conversation2->exists());
    }

    public function testCancelConversation()
    {
        $info = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $conversation->cancel();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertFalse($conversation2->exists());
    }

    public function testUpdateConversationNotes()
    {
        $info = TestHelpers::startFakeConversation();
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $conversation->notes = 'newnote';
        $conversation->update();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertSame('newnote', $conversation2->notes);

        $conversation3 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertSame('newnote', $conversation3->notes);
    }
}
