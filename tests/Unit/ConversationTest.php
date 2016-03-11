<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

use Tests\TestHelpers;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Telegram;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class ConversationTest extends TestCase
{
    /**
     * @var \Longman\TelegramBot\Telegram
     */
    private $telegram;

    /**
     * setUp
     */
    protected function setUp()
    {
        $credentials = [
            'host'     => '127.0.0.1',
            'user'     => 'travis',
            'password' => '',
            'database' => 'telegrambot',
        ];

        $this->telegram = new Telegram('testapikey', 'testbotname');
        $this->telegram->enableMySQL($credentials);

        //Make sure we start with an empty DB for each test.
        TestHelpers::emptyDB($credentials);
    }

    /**
     * @test
     */
    public function conversationThatDoesntExistPropertiesSetCorrectly()
    {
        $conversation = new Conversation(123, 456);
        $this->assertAttributeEquals(123, 'user_id', $conversation);
        $this->assertAttributeEquals(456, 'chat_id', $conversation);
        $this->assertAttributeEquals(null, 'command', $conversation);
    }

    /**
     * @test
     */
    public function conversationThatExistsPropertiesSetCorrectly()
    {
        $info = TestHelpers::startFakeConversation('command');
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertAttributeEquals($info['user_id'], 'user_id', $conversation);
        $this->assertAttributeEquals($info['chat_id'], 'chat_id', $conversation);
        $this->assertAttributeEquals('command', 'command', $conversation);
    }

    /**
     * @test
     */
    public function conversationThatDoesntExistWithoutCommand()
    {
        $conversation = new Conversation(1, 1);
        $this->assertFalse($conversation->exists());
        $this->assertNull($conversation->getCommand());
    }

    /**
     * @test
     * @expectedException \Longman\TelegramBot\Exception\TelegramException
     */
    public function conversationThatDoesntExistWithCommand()
    {
        new Conversation(1, 1, 'command');
    }

    /**
     * @test
     */
    public function newConversationThatWontExistWithoutCommand()
    {
        TestHelpers::startFakeConversation(null);
        $conversation = new Conversation(0, 0);
        $this->assertFalse($conversation->exists());
        $this->assertNull($conversation->getCommand());
    }

    /**
     * @test
     */
    public function newConversationThatWillExistWithCommand()
    {
        $info = TestHelpers::startFakeConversation('command');
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $this->assertEquals('command', $conversation->getCommand());
    }

    /**
     * @test
     */
    public function stopConversation()
    {
        $info = TestHelpers::startFakeConversation('command');
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $conversation->stop();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertFalse($conversation2->exists());
    }

    /**
     * @test
     */
    public function cancelConversation()
    {
        $info = TestHelpers::startFakeConversation('command');
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertTrue($conversation->exists());
        $conversation->cancel();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertFalse($conversation2->exists());
    }

    /**
     * @test
     */
    public function updateConversationNotes()
    {
        $info = TestHelpers::startFakeConversation('command');
        $conversation = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $conversation->notes = 'newnote';
        $conversation->update();

        $conversation2 = new Conversation($info['user_id'], $info['chat_id'], 'command');
        $this->assertSame('newnote', $conversation2->notes);

        $conversation3 = new Conversation($info['user_id'], $info['chat_id']);
        $this->assertSame('newnote', $conversation3->notes);
    }
}
