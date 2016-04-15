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

use \Longman\TelegramBot\Entities\Message;

/**
 * @package 		TelegramTest
 * @author 		Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright 		Avtandil Kikabidze <akalongman@gmail.com>
 * @license 		http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link 			http://www.github.com/akalongman/php-telegram-bot
 */
class MessageTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Telegram
    */
    private $message;
    
    /**
    * setUp
    */
    protected function setUp()
    {
    }



    protected function generateMessage($string) {


        //$string = addslashes($string);
        $string = str_replace("\n", "\\n", $string);
        $json = '{"message_id":961,"from":{"id":123,"first_name":"john","username":"john"},"chat":{"id":123,"title":null,"first_name":"john","last_name":null,"username":"null"},"date":1435920612,"text":"'.$string.'"}';
        //$json = utf8_encode($json);  
        return json_decode($json, true);
    }
    /**
     * @test
     */

    public function testTextAndCommandRecognise() {
        // /command
        $this->message = new Message($this->generateMessage('/help'), 'testbot');

        $this->assertEquals('/help', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals('/help', $this->message->getText());
        $this->assertEquals('', $this->message->getText(true));
  
        // text 
        $this->message = new Message($this->generateMessage('some text'), 'testbot');

        $this->assertEquals('', $this->message->getFullCommand());
        $this->assertEquals('', $this->message->getCommand());
        $this->assertEquals('some text', $this->message->getText());
        $this->assertEquals('some text', $this->message->getText(true));


        // /command@bot

        $this->message = new Message($this->generateMessage('/help@testbot'), 'testbot');
        $this->assertEquals('/help@testbot', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals('/help@testbot', $this->message->getText());
        $this->assertEquals('', $this->message->getText(true));

        // /commmad text
        $this->message = new Message($this->generateMessage('/help some text'), 'testbot');
        $this->assertEquals('/help', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals('/help some text', $this->message->getText());
        $this->assertEquals('some text', $this->message->getText(true));

        // /command@bot some text
        $this->message = new Message($this->generateMessage('/help@testbot some text'), 'testbot');
        $this->assertEquals('/help@testbot', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals('/help@testbot some text', $this->message->getText());
        $this->assertEquals('some text', $this->message->getText(true));

        // /commmad\n text

//$array = $this->generateMessage("/help\n some text");
////print_r($this->generateMessage('/help@testbot'));
//echo 'value:';
//print_r($array);
        $this->message = new Message($this->generateMessage("/help\n some text"), 'testbot');
        $this->assertEquals('/help', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals("/help\n some text", $this->message->getText());
        $this->assertEquals(' some text', $this->message->getText(true));

        // /command@bot\nsome text
        $this->message = new Message($this->generateMessage("/help@testbot\nsome text"), 'testbot');
        $this->assertEquals('/help@testbot', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals("/help@testbot\nsome text", $this->message->getText());
        $this->assertEquals('some text', $this->message->getText(true));

        // /command@bot \nsome text
        $this->message = new Message($this->generateMessage("/help@testbot \nsome text"), 'testbot');
        $this->assertEquals('/help@testbot', $this->message->getFullCommand());
        $this->assertEquals('help', $this->message->getCommand());
        $this->assertEquals("/help@testbot \nsome text", $this->message->getText());
        $this->assertEquals("\nsome text", $this->message->getText(true));
     }
}
