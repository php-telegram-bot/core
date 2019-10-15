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

namespace Longman\TelegramBot\Tests\Unit;

use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\PhotoSize;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Sticker;
use Longman\TelegramBot\Entities\StickerSet;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\UserProfilePhotos;
use Longman\TelegramBot\Request;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class ServerResponseTest extends TestCase
{
    protected function setUp(): void
    {
        // Make sure the current action in the Request class is unset.
        TestHelpers::setStaticProperty(Request::class, 'current_action', null);
    }

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

    public function testSendMessageOk()
    {
        $result        = $this->sendMessageOk();
        $server        = new ServerResponse(json_decode($result, true), 'testbot');
        $server_result = $server->getResult();

        self::assertTrue($server->isOk());
        self::assertNull($server->getErrorCode());
        self::assertNull($server->getDescription());
        self::assertInstanceOf(Message::class, $server_result);

        //Message
        self::assertEquals('1234', $server_result->getMessageId());
        self::assertEquals('123456789', $server_result->getFrom()->getId());
        self::assertEquals('botname', $server_result->getFrom()->getFirstName());
        self::assertEquals('namebot', $server_result->getFrom()->getUsername());
        self::assertEquals('123456789', $server_result->getChat()->getId());
        self::assertEquals('john', $server_result->getChat()->getFirstName());
        self::assertEquals('Mjohn', $server_result->getChat()->getUsername());
        self::assertEquals('1441378360', $server_result->getDate());
        self::assertEquals('hello', $server_result->getText());

        //... they are not finished...
    }

    public function sendMessageFail()
    {
        return '{
            "ok":false,
            "error_code":400,
            "description":"Error: Bad Request: wrong chat id"
        }';
    }

    public function testSendMessageFail()
    {
        $result = $this->sendMessageFail();
        $server = new ServerResponse(json_decode($result, true), 'testbot');

        self::assertFalse($server->isOk());
        self::assertNull($server->getResult());
        self::assertEquals('400', $server->getErrorCode());
        self::assertEquals('Error: Bad Request: wrong chat id', $server->getDescription());
    }

    public function setWebhookOk()
    {
        return '{"ok":true,"result":true,"description":"Webhook was set"}';
    }

    public function testSetWebhookOk()
    {
        $result = $this->setWebhookOk();
        $server = new ServerResponse(json_decode($result, true), 'testbot');

        self::assertTrue($server->isOk());
        self::assertTrue($server->getResult());
        self::assertNull($server->getErrorCode());
        self::assertEquals('Webhook was set', $server->getDescription());
    }

    public function setWebhookFail()
    {
        return '{
            "ok":false,
            "error_code":400,
            "description":"Error: Bad request: htttps:\/\/domain.host.org\/dir\/hook.php"
        }';
    }

    public function testSetWebhookFail()
    {
        $result = $this->setWebhookFail();
        $server = new ServerResponse(json_decode($result, true), 'testbot');

        self::assertFalse($server->isOk());
        self::assertNull($server->getResult());
        self::assertEquals(400, $server->getErrorCode());
        self::assertEquals('Error: Bad request: htttps://domain.host.org/dir/hook.php', $server->getDescription());
    }

    public function getUpdatesArray()
    {
        return '{
            "ok":true,
            "result":[
                {
                    "update_id":123,
                    "message":{
                        "message_id":90,
                        "from":{"id":123456789,"first_name":"John","username":"Mjohn"},
                        "chat":{"id":123456789,"first_name":"John","username":"Mjohn"},
                        "date":1441569067,
                        "text":"\/start"
                    }
                },
                {
                    "update_id":124,
                    "message":{
                        "message_id":91,
                        "from":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "chat":{"id":123456788,"first_name":"Patrizia","username":"Patry"},
                        "date":1441569073,
                        "text":"Hello!"
                    }
                },
                {
                    "update_id":125,
                    "message":{
                        "message_id":92,
                        "from":{"id":123456789,"first_name":"John","username":"MJohn"},
                        "chat":{"id":123456789,"first_name":"John","username":"MJohn"},
                        "date":1441569094,
                        "text":"\/echo hello!"
                    }
                },
                {
                    "update_id":126,
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

    public function testGetUpdatesArray()
    {
        $result = $this->getUpdatesArray();
        $server = new ServerResponse(json_decode($result, true), 'testbot');

        self::assertCount(4, $server->getResult());
        self::assertInstanceOf(Update::class, $server->getResult()[0]);
    }

    public function getUpdatesEmpty()
    {
        return '{"ok":true,"result":[]}';
    }

    public function testGetUpdatesEmpty()
    {
        $result = $this->getUpdatesEmpty();
        $server = new ServerResponse(json_decode($result, true), 'testbot');

        self::assertEmpty($server->getResult());
    }

    public function getUserProfilePhotos()
    {
        TestHelpers::setStaticProperty(Request::class, 'current_action', 'getUserProfilePhotos');
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
        $result        = $this->getUserProfilePhotos();
        $server        = new ServerResponse(json_decode($result, true), 'testbot');
        $server_result = $server->getResult();

        $photos = $server_result->getPhotos();

        //Photo count
        self::assertEquals(3, $server_result->getTotalCount());
        self::assertCount(3, $photos);
        //Photo size count
        self::assertCount(3, $photos[0]);

        self::assertInstanceOf(UserProfilePhotos::class, $server_result);
        self::assertInstanceOf(PhotoSize::class, $photos[0][0]);
    }

    public function getFile()
    {
        TestHelpers::setStaticProperty(Request::class, 'current_action', 'getFile');
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
        $server = new ServerResponse(json_decode($result, true), 'testbot');

        self::assertInstanceOf(File::class, $server->getResult());
    }

    public function testSetGeneralTestFakeResponse()
    {
        //setWebhook ok
        $fake_response = Request::generateGeneralFakeServerResponse();

        $server = new ServerResponse($fake_response, 'testbot');

        self::assertTrue($server->isOk());
        self::assertTrue($server->getResult());
        self::assertNull($server->getErrorCode());
        self::assertEquals('', $server->getDescription());

        //sendMessage ok
        $fake_response = Request::generateGeneralFakeServerResponse(['chat_id' => 123456789, 'text' => 'hello']);

        $server = new ServerResponse($fake_response, 'testbot');

        /** @var Message $server_result */
        $server_result = $server->getResult();

        self::assertTrue($server->isOk());
        self::assertNull($server->getErrorCode());
        self::assertNull($server->getDescription());
        self::assertInstanceOf(Message::class, $server_result);

        //Message
        self::assertEquals('1234', $server_result->getMessageId());
        self::assertEquals('1441378360', $server_result->getDate());
        self::assertEquals('hello', $server_result->getText());

        //Message //User
        self::assertEquals('123456789', $server_result->getFrom()->getId());
        self::assertEquals('botname', $server_result->getFrom()->getFirstName());
        self::assertEquals('namebot', $server_result->getFrom()->getUsername());

        //Message //Chat
        self::assertEquals('123456789', $server_result->getChat()->getId());
        self::assertEquals('', $server_result->getChat()->getFirstName());
        self::assertEquals('', $server_result->getChat()->getUsername());

        //... they are not finished...
    }

    public function getStickerSet()
    {
        TestHelpers::setStaticProperty(Request::class, 'current_action', 'getStickerSet');
        return '{
            "ok":true,
            "result":{
                "name":"stickerset_name",
                "title":"Some name",
                "contains_masks":false,
                "stickers":[
                    {
                        "width":512,
                        "height":324,
                        "emoji":"\ud83d\ude33",
                        "set_name":"stickerset_name",
                        "thumb":{"file_id":"AAQEABOKTFsZAASfA4t3pp1_VlH1AAIC","file_size":3120,"width":128,"height":81},
                        "file_id":"CAADBAADzAIAAph_7gOATSb9ehxv5QI",
                        "file_size":14246
                    },
                    {
                        "width":419,
                        "height":512,
                        "emoji":"\u2764",
                        "set_name":"stickerset_name",
                        "thumb":{"file_id":"AAQEABMj8qoZAASePUHuDSJ2uGIKAAIC","file_size":3500,"width":105,"height":128},
                        "file_id":"CAADBAADzQIAAph_7gNPFguft4qtjAI",
                        "file_size":17814
                    },
                    {
                        "width":512,
                        "height":276,
                        "emoji":"\ud83d\ude36",
                        "set_name":"stickerset_name",
                        "thumb":{"file_id":"AAQEABMiaWcZAATNUEPkYkd0Fh2JBAABAg","file_size":2642,"file_path":"thumbnails\/file_8.jpg","width":128,"height":69},
                        "file_id":"CAADBAADzwIAAph_7gOClxA3gK5wqAI",
                        "file_size":12258
                    },
                    {
                        "width":512,
                        "height":327,
                        "emoji":"\ud83d\udcbb",
                        "set_name":"stickerset_name",
                        "thumb":{"file_id":"AAQEABPC3d8ZAAQUJJnFB1VfII2RAAIC","file_size":3824,"file_path":"thumbnails\/file_10.jpg","width":128,"height":82},
                        "file_id":"CAADBAAD0QIAAph_7gO-vBJGkTeWqwI",
                        "file_size":18282
                    }
                ]
            }
        }';
    }

    public function testGetStickerSet()
    {
        $result = $this->getStickerSet();
        $server = new ServerResponse(json_decode($result, true), 'testbot');

        $server_result = $server->getResult();

        self::assertInstanceOf(StickerSet::class, $server_result);
        self::assertEquals('stickerset_name', $server_result->getName());
        self::assertEquals('Some name', $server_result->getTitle());
        self::assertFalse($server_result->getContainsMasks());

        $stickers = $server_result->getStickers();
        self::assertCount(4, $stickers);
        self::assertInstanceOf(Sticker::class, $stickers[0]);
    }
}
