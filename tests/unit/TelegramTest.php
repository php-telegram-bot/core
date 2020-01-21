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

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class TelegramTest extends TestCase
{
    use ArraySubsetAsserts;

    /**
     * @var Telegram
     */
    private $telegram;

    /**
     * @var array A few dummy custom commands paths
     */
    private $custom_commands_paths = [
        '/tmp/php-telegram-bot-custom-commands-1',
        '/tmp/php-telegram-bot-custom-commands-2',
        '/tmp/php-telegram-bot-custom-commands-3',
    ];

    protected function setUp(): void
    {
        $this->telegram = new Telegram(self::$dummy_api_key, 'testbot');

        // Create a few dummy custom commands paths.
        foreach ($this->custom_commands_paths as $custom_path) {
            mkdir($custom_path);
        }
    }

    protected function tearDown(): void
    {
        // Clean up the custom commands paths.
        foreach ($this->custom_commands_paths as $custom_path) {
            rmdir($custom_path);
        }
    }

    public function testNewInstanceWithoutApiKeyParam()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('API KEY not defined!');
        new Telegram(null, 'testbot');
    }

    public function testNewInstanceWithInvalidApiKeyParam()
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('Invalid API KEY defined!');
        new Telegram('invalid-api-key-format', null);
    }

    public function testGetApiKey()
    {
        $this->assertEquals(self::$dummy_api_key, $this->telegram->getApiKey());
    }

    public function testGetBotUsername()
    {
        $this->assertEquals('testbot', $this->telegram->getBotUsername());
    }

    public function testEnableAdmins()
    {
        $tg = $this->telegram;

        $this->assertEmpty($tg->getAdminList());

        // Single
        $tg->enableAdmin(1);
        $this->assertCount(1, $tg->getAdminList());

        // Multiple
        $tg->enableAdmins([2, 3]);
        $this->assertCount(3, $tg->getAdminList());

        // Already added
        $tg->enableAdmin(2);
        $this->assertCount(3, $tg->getAdminList());

        // Integer as a string
        $tg->enableAdmin('4');
        $this->assertCount(3, $tg->getAdminList());

        // Random string
        $tg->enableAdmin('a string?');
        $this->assertCount(3, $tg->getAdminList());
    }

    public function testAddCustomCommandsPaths()
    {
        $tg = $this->telegram;

        $this->assertCount(1, $tg->getCommandsPaths());

        $tg->addCommandsPath($this->custom_commands_paths[0]);
        $this->assertCount(2, $tg->getCommandsPaths());
        $this->assertArraySubset(
            [$this->custom_commands_paths[0]],
            $tg->getCommandsPaths()
        );

        $tg->addCommandsPath('/invalid/path');
        $this->assertCount(2, $tg->getCommandsPaths());

        $tg->addCommandsPaths([
            $this->custom_commands_paths[1],
            $this->custom_commands_paths[2],
        ]);
        $this->assertCount(4, $tg->getCommandsPaths());
        $this->assertArraySubset(
            array_reverse($this->custom_commands_paths),
            $tg->getCommandsPaths()
        );

        $tg->addCommandsPath($this->custom_commands_paths[0]);
        $this->assertCount(4, $tg->getCommandsPaths());
    }

    public function testGetCommandsList()
    {
        $commands = $this->telegram->getCommandsList();
        $this->assertIsArray($commands);
        $this->assertNotCount(0, $commands);
    }

    public function testUpdateFilter()
    {
        $rawUpdate = '{
            "update_id": 513400512,
            "message": {
                "message_id": 3,
                "from": {
                    "id": 313534466,
                    "first_name": "first",
                    "last_name": "last",
                    "username": "username"
                },
                "chat": {
                    "id": 313534466,
                    "first_name": "first",
                    "last_name": "last",
                    "username": "username",
                    "type": "private"
                },
                "date": 1499402829,
                "text": "hi"
            }
        }';

        $update = new Update(json_decode($rawUpdate, true), $this->telegram->getBotUsername());
        $this->telegram->setUpdateFilter(function ($update, $telegramInstance) {
            if ($update->getMessage()->getChat()->getId() == 313534466) {
                return false;
            }
            return true;
        });
        $response = $this->telegram->processUpdate($update);
        $this->assertFalse($response->isOk());
    }
}
