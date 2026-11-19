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
use Dummy\AdminCommands\DummyAdminCommand;
use Dummy\SystemCommands\DummySystemCommand;
use Dummy\UserCommands\DummyUserCommand;
use Longman\TelegramBot\Commands\UserCommands\StartCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
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

        unset($_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN']);
        TestHelpers::setStaticProperty(TelegramLog::class, 'logger', null);
        TestHelpers::setStaticProperty(TelegramLog::class, 'update_logger', null);
    }

    public function testNewInstanceWithoutApiKeyParam(): void
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('API KEY not defined!');
        new Telegram('');
    }

    public function testNewInstanceWithInvalidApiKeyParam(): void
    {
        $this->expectException(TelegramException::class);
        $this->expectExceptionMessage('Invalid API KEY defined!');
        new Telegram('invalid-api-key-format');
    }

    public function testGetApiKey(): void
    {
        self::assertEquals(self::$dummy_api_key, $this->telegram->getApiKey());
    }

    public function testGetBotUsername(): void
    {
        self::assertEquals('testbot', $this->telegram->getBotUsername());
    }

    public function testEnableAdmins(): void
    {
        $tg = $this->telegram;

        self::assertEmpty($tg->getAdminList());

        // Single
        $tg->enableAdmin(1);
        self::assertCount(1, $tg->getAdminList());

        // Multiple
        $tg->enableAdmins([2, 3]);
        self::assertCount(3, $tg->getAdminList());

        // Already added
        $tg->enableAdmin(2);
        self::assertCount(3, $tg->getAdminList());
    }

    public function testAddCustomCommandsPaths(): void
    {
        $tg = $this->telegram;

        self::assertCount(1, $tg->getCommandsPaths());

        $tg->addCommandsPath($this->custom_commands_paths[0]);
        self::assertCount(2, $tg->getCommandsPaths());
        self::assertArraySubset(
            [$this->custom_commands_paths[0]],
            $tg->getCommandsPaths()
        );

        $tg->addCommandsPath('/invalid/path');
        self::assertCount(2, $tg->getCommandsPaths());

        $tg->addCommandsPaths([
            $this->custom_commands_paths[1],
            $this->custom_commands_paths[2],
        ]);
        self::assertCount(4, $tg->getCommandsPaths());
        self::assertArraySubset(
            array_reverse($this->custom_commands_paths),
            $tg->getCommandsPaths()
        );

        $tg->addCommandsPath($this->custom_commands_paths[0]);
        self::assertCount(4, $tg->getCommandsPaths());
    }

    public function testAddCustomCommandsClass(): void
    {
        $tg = $this->telegram;

        // Require dummy commands to test with
        require_once __DIR__ . '/Commands/CustomTestCommands/DummySystemCommand.php';
        require_once __DIR__ . '/Commands/CustomTestCommands/DummyAdminCommand.php';
        require_once __DIR__ . '/Commands/CustomTestCommands/DummyUserCommand.php';

        // Test for base arrays (System, Admin, User)
        self::assertCount(3, $tg->getCommandClasses());

        // Test for invalid command classes
        try {
            $tg->addCommandClass('');
        } catch (\InvalidArgumentException $ex) {
        }
        self::assertEmpty(array_filter($tg->getCommandClasses()));

        try {
            $tg->addCommandClass('not\exist\Class');
        } catch (\InvalidArgumentException $ex) {
        }
        self::assertEmpty(array_filter($tg->getCommandClasses()));

        // Add valid command classes
        $tg->addCommandClass(DummySystemCommand::class);
        $tg->addCommandClasses([
            DummyAdminCommand::class,
            DummyUserCommand::class,
        ]);

        $command_classes = $tg->getCommandClasses();
        self::assertSame(['dummy_system' => 'Dummy\SystemCommands\DummySystemCommand'], $command_classes['System']);
        self::assertSame(['dummy_admin' => 'Dummy\AdminCommands\DummyAdminCommand'], $command_classes['Admin']);
        self::assertSame(['dummy_user' => 'Dummy\UserCommands\DummyUserCommand'], $command_classes['User']);
    }

    public function testSettingDownloadUploadPaths(): void
    {
        self::assertEmpty($this->telegram->getDownloadPath());
        self::assertEmpty($this->telegram->getUploadPath());

        $this->telegram->setDownloadPath('/down/below');
        $this->telegram->setUploadPath('/up/above');

        self::assertSame('/down/below', $this->telegram->getDownloadPath());
        self::assertSame('/up/above', $this->telegram->getUploadPath());
    }

    public function testGetCommandsList(): void
    {
        $commands = $this->telegram->getCommandsList();
        self::assertIsArray($commands);
        self::assertNotCount(0, $commands);
    }

    public function testGetCommandClass(): void
    {
        $className = StartCommand::class;
        $commands  = $this->telegram->getCommandClasses();
        self::assertIsArray($commands);
        self::assertCount(3, $commands);

        $class = $this->telegram->getCommandClassName('user', 'notexist');
        self::assertNull($class);

        $this->telegram->addCommandClass($className);
        $class = $this->telegram->getCommandClassName('user', 'start');
        self::assertNotNull($class);

        self::assertSame($className, $class);
    }

    public function testUpdateFilter(): void
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

        $debug_log_file = '/tmp/php-telegram-bot-update-filter-debug.log';
        TelegramLog::initialize(
            new \Monolog\Logger('bot_log', [
                (new \Monolog\Handler\StreamHandler($debug_log_file, \Monolog\Logger::DEBUG))->setFormatter(new \Monolog\Formatter\LineFormatter(null, null, true)),
            ])
        );

        $update = new Update(json_decode($rawUpdate, true), $this->telegram->getBotUsername());
        $this->telegram->setUpdateFilter(function (Update $update, Telegram $telegram, &$reason) {
            if ($update->getMessage()->getChat()->getId() === 313534466) {
                $reason = 'Invalid user, update denied.';
                return false;
            }
            return true;
        });
        $response = $this->telegram->processUpdate($update);
        self::assertFalse($response->isOk());

        // Check that the reason is written to the debug log.
        $debug_log = file_get_contents($debug_log_file);
        self::assertStringContainsString('Invalid user, update denied.', $debug_log);
        file_exists($debug_log_file) && unlink($debug_log_file);
    }

    public function testWebhookSecretTokenIsEmptyByDefault(): void
    {
        self::assertSame('', $this->telegram->getWebhookSecretToken());
    }

    public function testSetWebhookSecretToken(): void
    {
        self::assertSame($this->telegram, $this->telegram->setWebhookSecretToken('Kq7_x-41'));
        self::assertSame('Kq7_x-41', $this->telegram->getWebhookSecretToken());
    }

    public function testSetWebhookSecretTokenAcceptsTheLongestToken(): void
    {
        $token = str_repeat('aZ0_-', 51) . 'a';

        self::assertSame(256, strlen($token));
        self::assertSame($token, $this->telegram->setWebhookSecretToken($token)->getWebhookSecretToken());
    }

    /**
     * @dataProvider invalidWebhookSecretTokenProvider
     */
    public function testSetWebhookSecretTokenRefusesWhatTelegramRefuses(string $token): void
    {
        $this->expectException(TelegramException::class);

        $this->telegram->setWebhookSecretToken($token);
    }

    public function invalidWebhookSecretTokenProvider(): array
    {
        return [
            'empty'            => [''],
            'too long'         => [str_repeat('a', 257)],
            'space'            => ['Kq7 x41'],
            'dot'              => ['Kq7.x41'],
            'non-ASCII letter' => ['Kq7_x-41ü'],
            'trailing newline' => ["Kq7_x-41\n"],
        ];
    }

    public function testRefusedWebhookSecretTokenKeepsThePreviousOne(): void
    {
        $this->telegram->setWebhookSecretToken('Kq7_x-41');

        try {
            $this->telegram->setWebhookSecretToken('not a token');
            self::fail('The token was accepted');
        } catch (TelegramException $e) {
            self::assertSame('Kq7_x-41', $this->telegram->getWebhookSecretToken());
        }
    }

    public function testHandleProcessesRequestWithMatchingSecretToken(): void
    {
        $handler = $this->updateLogHandler();
        $this->telegram->setWebhookSecretToken('Kq7_x-41');
        $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] = 'Kq7_x-41';
        $this->telegram->setCustomInput($this->webhookInput());

        self::assertTrue($this->telegram->handle());
        self::assertSame(77, $this->telegram->getLastUpdateId());
        self::assertCount(1, $handler->getRecords());
    }

    /**
     * @dataProvider refusedSecretTokenHeaderProvider
     *
     * @param mixed $header The header value, null for a request without the header
     */
    public function testHandleRefusesRequestWithoutMatchingSecretToken($header): void
    {
        $handler = $this->updateLogHandler();
        $this->telegram->setWebhookSecretToken('Kq7_x-41');
        if ($header !== null) {
            $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] = $header;
        }
        $this->telegram->setCustomInput($this->webhookInput());

        try {
            $this->telegram->handle();
            self::fail('The request was processed');
        } catch (TelegramException $e) {
            self::assertStringNotContainsString('Kq7_x-41', $e->getMessage());
        }

        self::assertNull($this->telegram->getLastUpdateId());
        self::assertSame([], $handler->getRecords());
    }

    public function refusedSecretTokenHeaderProvider(): array
    {
        return [
            'no header'      => [null],
            'empty'          => [''],
            'different'      => ['guess'],
            'shorter'        => ['Kq7_x-4'],
            'different case' => ['kq7_x-41'],
            'leading space'  => [' Kq7_x-41'],
            'not a string'   => [['Kq7_x-41']],
        ];
    }

    /**
     * @dataProvider anySecretTokenHeaderProvider
     */
    public function testHandleWithoutConfiguredSecretTokenProcessesEveryRequest(?string $header): void
    {
        if ($header !== null) {
            $_SERVER['HTTP_X_TELEGRAM_BOT_API_SECRET_TOKEN'] = $header;
        }
        $this->telegram->setCustomInput($this->webhookInput());

        self::assertTrue($this->telegram->handle());
        self::assertSame(77, $this->telegram->getLastUpdateId());
    }

    public function anySecretTokenHeaderProvider(): array
    {
        return [
            'no header'   => [null],
            'some header' => ['anything'],
        ];
    }

    public function testWebhookDataKeepsOnlyKnownParameters(): void
    {
        $data = $this->telegramExposingWebhookData()->webhookData(
            'https://example.com/hook.php',
            ['max_connections' => 5, 'unknown' => 1]
        );

        self::assertEquals(['url' => 'https://example.com/hook.php', 'max_connections' => 5], $data);
    }

    public function testWebhookDataCarriesTheConfiguredSecretToken(): void
    {
        $telegram = $this->telegramExposingWebhookData();
        $telegram->setWebhookSecretToken('Kq7_x-41');

        self::assertEquals(
            ['url' => 'https://example.com/hook.php', 'secret_token' => 'Kq7_x-41'],
            $telegram->webhookData('https://example.com/hook.php')
        );
    }

    public function testWebhookDataPrefersTheSecretTokenPassedIn(): void
    {
        $telegram = $this->telegramExposingWebhookData();
        $telegram->setWebhookSecretToken('Kq7_x-41');

        $data = $telegram->webhookData('https://example.com/hook.php', ['secret_token' => 'Other-1']);

        self::assertSame('Other-1', $data['secret_token']);
    }

    public function testWebhookDataHasNoSecretTokenWhenNoneIsConfigured(): void
    {
        $data = $this->telegramExposingWebhookData()->webhookData('https://example.com/hook.php');

        self::assertArrayNotHasKey('secret_token', $data);
    }

    public function testSetWebhookWithConfiguredSecretToken(): void
    {
        $this->telegram->setWebhookSecretToken('Kq7_x-41');

        self::assertTrue($this->telegram->setWebhook('https://example.com/hook.php')->isOk());
    }

    /**
     * Return the JSON body of a webhook request that carries a plain text message
     *
     * @return string
     */
    private function webhookInput(): string
    {
        return json_encode([
            'update_id' => 77,
            'message'   => [
                'message_id' => 1,
                'date'       => 1700000000,
                'from'       => ['id' => 1, 'is_bot' => false, 'first_name' => 'a'],
                'chat'       => ['id' => 1, 'type' => 'private'],
                'text'       => 'hello',
            ],
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * Send the update log to a test handler and return that handler
     *
     * @return \Monolog\Handler\TestHandler
     */
    private function updateLogHandler(): \Monolog\Handler\TestHandler
    {
        $handler = new \Monolog\Handler\TestHandler();
        TelegramLog::initialize(null, new \Monolog\Logger('updates', [$handler]));

        return $handler;
    }

    /**
     * Return a Telegram object that exposes the request parameters setWebhook() sends
     *
     * @return Telegram
     */
    private function telegramExposingWebhookData(): Telegram
    {
        return new class (self::$dummy_api_key, 'testbot') extends Telegram {
            /**
             * Return the request parameters setWebhook() would send for these arguments
             *
             * @param string $url
             * @param array  $data
             *
             * @return array
             */
            public function webhookData(string $url, array $data = []): array
            {
                return $this->buildWebhookData($url, $data);
            }
        };
    }
}
