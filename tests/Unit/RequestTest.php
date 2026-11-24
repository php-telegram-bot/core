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

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Monolog\Handler\TestHandler;
use Monolog\Logger;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         RequestTest
 */
class RequestTest extends TestCase
{
    /**
     * @var array The static properties a test changes, by class, as they were before it
     */
    private $saved_static_properties = [];

    protected function setUp(): void
    {
        foreach ($this->staticPropertiesToRestore() as [$class, $property]) {
            $saved_value = (new \ReflectionProperty($class, $property))->getValue();

            $this->saved_static_properties[$class][$property] = $saved_value;
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->saved_static_properties as $class => $properties) {
            foreach ($properties as $property => $value) {
                TestHelpers::setStaticProperty($class, $property, $value);
            }
        }
    }

    public function testExecuteKeepsTheSecretTokenOutOfTheDebugLog(): void
    {
        [$mock, $handler] = $this->executeAgainstFailingTelegram(
            'setWebhook',
            ['url' => 'https://example.com/hook.php', 'secret_token' => 'Kq7_x-41']
        );

        self::assertStringContainsString('secret_token=Kq7_x-41', (string) $mock->getLastRequest()->getBody());
        foreach ($handler->getRecords() as $record) {
            self::assertStringNotContainsString('Kq7_x-41', $record['message']);
        }
        self::assertTrue($handler->hasDebugThatContains('[secret_token] => SECRET_TOKEN_REMOVED'));
    }

    /**
     * A secret token that PHP treats as falsy or that is not a string is still kept out of the debug log
     *
     * The bare value is not checked: '0' also occurs in unrelated log text, such as the 400 status.
     *
     * @dataProvider zeroOrIntegerSecretTokenProvider
     *
     * @param int|string $secret_token
     */
    public function testExecuteKeepsAZeroOrIntegerSecretTokenOutOfTheDebugLog(int|string $secret_token): void
    {
        $data = ['url' => 'https://example.com/hook.php', 'secret_token' => $secret_token];

        [, $handler] = $this->executeAgainstFailingTelegram('setWebhook', $data);

        foreach ($handler->getRecords() as $record) {
            self::assertStringNotContainsString('[secret_token] => ' . $secret_token, $record['message']);
        }
        self::assertTrue($handler->hasDebugThatContains('[secret_token] => SECRET_TOKEN_REMOVED'));
    }

    /**
     * Return secret tokens that a check on emptiness or on the string type would log in clear
     *
     * @return array
     */
    public function zeroOrIntegerSecretTokenProvider(): array
    {
        return [
            'zero string' => ['0'],
            'integer'     => [12345],
        ];
    }

    public function testExecuteLogsRequestDataWithoutSecretTokenUnchanged(): void
    {
        $data = ['chat_id' => 1, 'text' => 'hello'];

        [, $handler] = $this->executeAgainstFailingTelegram('sendMessage', $data);

        self::assertTrue($handler->hasDebug('Request data:' . PHP_EOL . print_r($data, true)));
    }

    /**
     * @dataProvider emptySecretTokenProvider
     */
    public function testExecuteLogsASecretTokenThatCarriesNoTokenUnchanged(?string $secret_token): void
    {
        $data = ['url' => 'https://example.com/hook.php', 'secret_token' => $secret_token];

        [, $handler] = $this->executeAgainstFailingTelegram('setWebhook', $data);

        foreach ($handler->getRecords() as $record) {
            self::assertStringNotContainsString('SECRET_TOKEN_REMOVED', $record['message']);
        }
        self::assertTrue($handler->hasDebug('Request data:' . PHP_EOL . print_r($data, true)));
    }

    public function emptySecretTokenProvider(): array
    {
        return [
            'null'         => [null],
            'empty string' => [''],
        ];
    }

    /**
     * Execute a request against a client that answers 400, with a debug logger that records every line
     *
     * @param string $action
     * @param array  $data
     *
     * @return array The Guzzle mock handler and the Monolog test handler
     */
    private function executeAgainstFailingTelegram(string $action, array $data): array
    {
        // Request::execute() takes the API key from the Telegram object that initialized Request.
        new Telegram(self::$dummy_api_key, 'testbot');

        $mock = new MockHandler([
            new Response(400, [], '{"ok":false,"error_code":400,"description":"Bad Request"}'),
        ]);
        Request::setClient(new Client([
            'base_uri' => 'https://api.telegram.org',
            'handler'  => HandlerStack::create($mock),
        ]));

        $handler = new TestHandler();
        TelegramLog::initialize(new Logger('bot_log', [$handler]));

        Request::execute($action, $data);

        return [$mock, $handler];
    }

    /**
     * Return the static properties a test in this class changes, as [class, property] pairs
     *
     * @return array
     */
    private function staticPropertiesToRestore(): array
    {
        return [
            [Request::class, 'client'],
            [Request::class, 'telegram'],
            [TelegramLog::class, 'logger'],
            [TelegramLog::class, 'update_logger'],
        ];
    }
}
