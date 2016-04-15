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

use \Longman\TelegramBot\Entities\Update;
use \Longman\TelegramBot\Entities\ReplyToMessage;

/**
 * @package 		TelegramTest
 * @author 		Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright 		Avtandil Kikabidze <akalongman@gmail.com>
 * @license 		http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link 			http://www.github.com/akalongman/php-telegram-bot
 */
class ReplyToMessageTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Telegram
    */
    private $reply_to_message;
    private $message;

    /**
     * @test
     */
 
    public function testChatType() 
    {
        $json = '
{"update_id":137809335,
"message":{"message_id":4479,"from":{"id":123,"first_name":"John","username":"MJohn"},"chat":{"id":-123,"title":"MyChat","type":"group"},"date":1449092987,"reply_to_message":{"message_id":11,"from":{"id":121,"first_name":"Myname","username":"mybot"},"chat":{"id":-123,"title":"MyChat","type":"group"},"date":1449092984,"text":"type some text"},"text":"some text"}}
';
        $struct = json_decode($json, true);
        $update = new Update($struct,'mybot');

        $this->message = $update->getMessage();
        $this->reply_to_message = $this->message->getReplyToMessage();

        $this->assertNull($this->reply_to_message->getReplyToMessage());

    }
}
