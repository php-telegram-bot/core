<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by Marco Boretto
 */
namespace Tests\Unit;

use \Longman\TelegramBot\Entities\ServerResponse;
use \Longman\TelegramBot\Entities\Message;
use \Longman\TelegramBot\Request;

/**
 * @package 		TelegramTest
 * @author 		Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright 		Avtandil Kikabidze <akalongman@gmail.com>
 * @license 		http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link 			http://www.github.com/akalongman/php-telegram-bot
 */
class ServerResponseTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Telegram
    */
    private $server;
    
    /**
    * setUp
    */
    protected function setUp()
    {
    }

    /**
     * @test
     */

    public function sendMessageOk() 
    {
        return '{
            "ok":true,
            "result":{
                "message_id":1234,
                "from":{"id":123456789,"first_name":"botname","username":"namebot"},
                "chat":{"id":123456789,"first_name":"john","username":"Mjohn"},
                "date":1441378360,
                "text":"hello"
                }
            }';
    }

    public function testSendMessageOk() {

        $result = $this->sendMessageOk(); 

        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\Message', $this->server->getResult());
        $this->assertNull($this->server->getErrorCode());
        $this->assertNull($this->server->getDescription());

        //Message
        $this->assertEquals('1234', $this->server->getResult()->getMessageId());
        $this->assertEquals('123456789', $this->server->getResult()->getFrom()->getId());
        $this->assertEquals('botname', $this->server->getResult()->getFrom()->getFirstName());
        $this->assertEquals('namebot', $this->server->getResult()->getFrom()->getUserName());
        $this->assertEquals('123456789', $this->server->getResult()->getChat()->getId());
        $this->assertEquals('john', $this->server->getResult()->getChat()->getFirstName());
        $this->assertEquals('Mjohn', $this->server->getResult()->getChat()->getUserName());
        $this->assertEquals('1441378360', $this->server->getResult()->getDate());
        $this->assertEquals('hello', $this->server->getResult()->getText());
        //... they are not finished...

    }

    /**
     * @test
     */

    public function sendMessageFail() 
    {
        return '{
                "ok":false,
                "error_code":400,
                "description":"Error: Bad Request: wrong chat id"
            }';
    }

    public function testSendMessageFail() {

        $result = $this->sendMessageFail();

        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertFalse($this->server->isOk());
        $this->assertNull($this->server->getResult());
        $this->assertEquals('400', $this->server->getErrorCode());
        $this->assertEquals('Error: Bad Request: wrong chat id', $this->server->getDescription());
    }

    /**
     * @test
     */

   public function setWebHookOk()
    {
        return '{"ok":true,"result":true,"description":"Webhook was set"}';
    }  

    public function testSetWebhookOk() {

        $result = $this->setWebhookOk();

        $this->server =  new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertTrue($this->server->getResult());
        $this->assertNull($this->server->getErrorCode());
        $this->assertEquals('Webhook was set', $this->server->getDescription());
    }

    /**
     * @test
     */

    public function setWebHookFail()
    {
        return '{
            "ok":false,
            "error_code":400,
            "description":"Error: Bad request: htttps:\/\/domain.host.org\/dir\/hook.php"
            }';
    }


    public function testSetWebhookFail() {

        $result = $this->setWebHookFail();

        $this->server =  new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertFalse($this->server->isOk());
        $this->assertNull($this->server->getResult());
        $this->assertEquals(400, $this->server->getErrorCode());
        $this->assertEquals("Error: Bad request: htttps://domain.host.org/dir/hook.php", $this->server->getDescription());
    }


    public function testSetGeneralTestFakeResponse() {
        //setWebhook ok
        $fake_response = Request::generateGeneralFakeServerSesponse();

        $this->server =  new ServerResponse($fake_response, 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertTrue($this->server->getResult());
        $this->assertNull($this->server->getErrorCode());
        $this->assertEquals('', $this->server->getDescription());


        //sendMessage ok
        $fake_response = Request::generateGeneralFakeServerSesponse(['chat_id' => 123456789, 'text' => 'hello']);

        $this->server =  new ServerResponse($fake_response, 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\Message', $this->server->getResult());
        $this->assertNull($this->server->getErrorCode());
        $this->assertNull($this->server->getDescription());

        //Message
        $this->assertEquals('1234', $this->server->getResult()->getMessageId());
        $this->assertEquals('1441378360', $this->server->getResult()->getDate());
        $this->assertEquals('hello', $this->server->getResult()->getText());
        //Message //User
        $this->assertEquals('123456789', $this->server->getResult()->getFrom()->getId());
        $this->assertEquals('botname', $this->server->getResult()->getFrom()->getFirstName());
        $this->assertEquals('namebot', $this->server->getResult()->getFrom()->getUserName());
        //Message //Chat
        $this->assertEquals('123456789', $this->server->getResult()->getChat()->getId());
        $this->assertEquals('', $this->server->getResult()->getChat()->getFirstName());
        $this->assertEquals('', $this->server->getResult()->getChat()->getUserName());

        //... they are not finished...
    }
}
