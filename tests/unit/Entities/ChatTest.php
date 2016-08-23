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
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class ChatTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Entities\Chat
    */
    private $chat;

    public function testChatType()
    {
        $this->chat = TestHelpers::getFakeChatObject();
        $this->assertEquals('private', $this->chat->getType());

        $this->chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => null]);
        $this->assertEquals('group', $this->chat->getType());

        $this->chat = TestHelpers::getFakeChatObject(['id' => -123, 'type' => 'channel']);
        $this->assertEquals('channel', $this->chat->getType());
    }
}
