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

use \Longman\TelegramBot\Entities\Chat;

/**
 * @package 		TelegramTest
 * @author 		Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright 		Avtandil Kikabidze <akalongman@gmail.com>
 * @license 		http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link 			http://www.github.com/akalongman/php-telegram-bot
 */
class ChatTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Telegram
    */
    private $chat;
    
    /**
    * setUp
    */
    protected function setUp()
    {
    }

    /**
     * @test
     */
 
    public function testChatType() 
    {

        $this->chat = new Chat(json_decode('{"id":123,"title":null,"first_name":"john","last_name":null,"username":"null"}',true));
        $this->assertEquals('private', $this->chat->getType());

        $this->chat = new Chat(json_decode('{"id":-123,"title":"ChatTitle","first_name":null,"last_name":null,"username":"null"}',true));
        $this->assertEquals('group', $this->chat->getType());

        $this->chat = new Chat(json_decode('{"id":-123,"type":"channel","title":"ChatTitle","first_name":null,"last_name":null,"username":"null"}',true));
        $this->assertEquals('channel', $this->chat->getType());
    }
}
