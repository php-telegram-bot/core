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

use Longman\TelegramBot\TelegramLog;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class TelegramLogTest extends TestCase
{
    /**
     * @var array Dummy logfile paths
     */
    private static $logfiles = [
        'debug'  => '/tmp/php-telegram-bot-debug.log',
        'error'  => '/tmp/php-telegram-bot-error.log',
        'update' => '/tmp/php-telegram-bot-update.log',
    ];

    protected function setUp(): void
    {
        TelegramLog::initialize(
            new Logger('bot_log', [
                (new StreamHandler(self::$logfiles['debug'], Logger::DEBUG))->setFormatter(new LineFormatter(null, null, true)),
                (new StreamHandler(self::$logfiles['error'], Logger::ERROR))->setFormatter(new LineFormatter(null, null, true)),
            ]),
            new Logger('bot_log_updates', [
                (new StreamHandler(self::$logfiles['update'], Logger::INFO))->setFormatter(new LineFormatter('%message%' . PHP_EOL)),
            ])
        );
    }

    protected function tearDown(): void
    {
        // Make sure no logger instance is set after each test.
        TestHelpers::setStaticProperty(TelegramLog::class, 'logger', null);
        TestHelpers::setStaticProperty(TelegramLog::class, 'update_logger', null);

        // Make sure no logfiles exist.
        foreach (self::$logfiles as $file) {
            file_exists($file) && unlink($file);
        }
    }

    public function testNullLogger()
    {
        TelegramLog::initialize(null, null);

        TelegramLog::debug('my debug log');
        TelegramLog::error('my error log');
        TelegramLog::update('my update log');

        foreach (self::$logfiles as $file) {
            $this->assertFileNotExists($file);
        }
    }

    public function testDebugStream()
    {
        $file = self::$logfiles['debug'];

        $this->assertFileNotExists($file);
        TelegramLog::debug('my debug log');
        TelegramLog::debug('my {place} {holder} debug log', ['place' => 'custom', 'holder' => 'placeholder']);

        $this->assertFileExists($file);
        $debug_log = file_get_contents($file);
        $this->assertStringContainsString('bot_log.DEBUG: my debug log', $debug_log);
        $this->assertStringContainsString('bot_log.DEBUG: my custom placeholder debug log', $debug_log);
    }

    public function testErrorStream()
    {
        $file = self::$logfiles['error'];

        $this->assertFileNotExists($file);
        TelegramLog::error('my error log');
        TelegramLog::error('my {place} {holder} error log', ['place' => 'custom', 'holder' => 'placeholder']);

        $this->assertFileExists($file);
        $error_log = file_get_contents($file);
        $this->assertStringContainsString('bot_log.ERROR: my error log', $error_log);
        $this->assertStringContainsString('bot_log.ERROR: my custom placeholder error log', $error_log);
    }

    public function testUpdateStream()
    {
        $file = self::$logfiles['update'];

        $this->assertFileNotExists($file);
        TelegramLog::update('my update log');
        TelegramLog::update('my {place} {holder} update log', ['place' => 'custom', 'holder' => 'placeholder']);

        $this->assertFileExists($file);
        $update_log = file_get_contents($file);
        $this->assertStringContainsString('my update log', $update_log);
        $this->assertStringContainsString('my custom placeholder update log', $update_log);
    }
}
