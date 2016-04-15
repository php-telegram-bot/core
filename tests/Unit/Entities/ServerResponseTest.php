<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Written by Marco Boretto
 */

namespace Tests\Unit;

use \Longman\TelegramBot\Entities\ServerResponse;
use \Longman\TelegramBot\Entities\Message;
use \Longman\TelegramBot\Request;

/**
 * @package         TelegramTest
 * @author             Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright         Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link             http://www.github.com/akalongman/php-telegram-bot
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



    /**
     * @test
     */

    public function getUpdatesArray()
    {
        return '{
            "ok":true,
            "result":[
                {"update_id":123,
                    "message":{
                        "message_id":90,
                        "from":{"id":123456789,"first_name":"John","username":"Mjohn"},
                        "chat":{"id":123456789,"first_name":"John","username":"Mjohn"},
                        "date":1441569067,
                        "text":"\/start"}
                },
                {"update_id":124,
                    "message":{
                        "message_id":91,
                        "from":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "chat":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "date":1441569073,
                        "text":"Hello!"}
                    },
                {"update_id":125,
                    "message":{
                        "message_id":92,
                        "from":{"id":123456789,"first_name":"John","username":"MJohn"},
                        "chat":{"id":123456789,"first_name":"John","username":"MJohn"},
                        "date":1441569094,
                        "text":"\/echo hello!"}
                    },
                {"update_id":126,
                "message":{
                    "message_id":93,
                    "from":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                    "chat":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                    "date":1441569112,
                    "text":"\/echo the best"
                    }
                }
            ]
        }';
    }


    public function testGetUpdatesArray() {
        $result = $this->getUpdatesArray();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertCount(4, $this->server->getResult());

        $this->assertInstanceOf('\Longman\TelegramBot\Entities\Update', $this->server->getResult()[0]);
    }

    /**
     * @test
     */

    public function getUpdatesEmpty()
    {
        return '{"ok":true,"result":[]}';
    }


    public function testGetUpdatesEmpty() {
        $result = $this->getUpdatesEmpty();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');
        $this->assertNull($this->server->getResult());
    }


    /**
     * @test
     */


    public function getUserProfilePhotos()
    {
        return '{
            "ok":true,
            "result":{
                "total_count":3,
                "photos":[
                    [
                        {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHqgg5uEju-8IBAAEC","file_size":7402,"width":160,"height":160},
                        {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHWMuphij6_MIBAAEC","file_size":15882,"width":320,"height":320},
                        {"file_id":"AgADBG6_vmQaVf3qOGVurBRzHNWdpQ9jz_cIBAAEC","file_size":46680,"width":640,"height":640}
                    ],
                    [
                        {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABO8UCGz_JLAAgI","file_size":7324,"width":160,"height":160},
                        {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABAlhB5Q_K0AAgI","file_size":15816,"width":320,"height":320},
                        {"file_id":"AgADBAADr6cxG6_vmH-bksDdiYzAABIIxOSHyayAAgI","file_size":46620,"width":640,"height":640}
                    ],
                    [
                        {"file_id":"AgABxG6_vmQaL2X0CUTAABMhd1n2RLaRSj6cAAgI","file_size":2710,"width":160,"height":160},
                        {"file_id":"AgADcxG6_vmQaL2X0EUTAABPXm1og0O7qwjKcAAgI","file_size":11660,"width":320,"height":320},
                        {"file_id":"AgADxG6_vmQaL2X0CUTAABMOtcfUmoPrcjacAAgI","file_size":37150,"width":640,"height":640}
                    ]
                ]
            }
        }';
    }


    public function testGetUserProfilePhotos()
    {
        $result = $this->getUserProfilePhotos();
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');

        $this->assertCount(3, $this->server->getResult()->getPhotos());
        $this->assertCount(3, $this->server->getResult()->getPhotos()[0]);
        $this->assertInstanceOf('\Longman\TelegramBot\Entities\UserProfilePhotos', $this->server->getResult());

        $this->assertInstanceOf('\Longman\TelegramBot\Entities\PhotoSize', $this->server->getResult()->getPhotos()[0][0]);

    }


    /**
     * @test
     */


    public function getFile()
    {
        return '{
            "ok":true,
            "result":{
                "file_id":"AgADBxG6_vmQaVf3qRzHYTAABD1hNWdpQ9qz_cIBAAEC",
                "file_size":46680,
                "file_path":"photo\/file_1.jpg"
            }
        }';
    }


    public function testGetFile()
    {
        $result = $this->getFile();
        //print_r(json_decode($result, true));
        $this->server = new ServerResponse(json_decode($result, true), 'testbot');
        //var_dump($this->server->getResult()->getPhotos());

        $this->assertInstanceOf('\Longman\TelegramBot\Entities\File', $this->server->getResult());

    }


    /**
     * @test
     */

    public function testSetGeneralTestFakeResponse() {
        //setWebhook ok
        $fake_response = Request::generateGeneralFakeServerResponse();

        $this->server =  new ServerResponse($fake_response, 'testbot');

        $this->assertTrue($this->server->isOk());
        $this->assertTrue($this->server->getResult());
        $this->assertNull($this->server->getErrorCode());
        $this->assertEquals('', $this->server->getDescription());


        //sendMessage ok
        $fake_response = Request::generateGeneralFakeServerResponse(['chat_id' => 123456789, 'text' => 'hello']);

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
