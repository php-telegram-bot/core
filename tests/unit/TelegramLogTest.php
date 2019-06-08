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
        'error'    => '/tmp/php-telegram-bot-errorlog.log',
        'debug'    => '/tmp/php-telegram-bot-debuglog.log',
        'update'   => '/tmp/php-telegram-bot-updatelog.log',
        'external' => '/tmp/php-telegram-bot-externallog.log',
    ];

    protected function setUp()
    {
        // Make sure no logger instance is set before each test.
        TestHelpers::setStaticProperty('Longman\TelegramBot\TelegramLog', 'logger', null);
        TestHelpers::setStaticProperty('Longman\TelegramBot\TelegramLog', 'update_logger', null);
    }

    protected function tearDown()
    {
        // Make sure no logfiles exist.
        foreach (self::$logfiles as $file) {
            file_exists($file) && unlink($file);
        }
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramLogException
     */
    public function testNewInstanceWithoutErrorPath()
    {
        TelegramLog::initErrorLog('');
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramLogException
     */
    public function testNewInstanceWithoutDebugPath()
    {
        TelegramLog::initDebugLog('');
    }

    /**
     * @expectedException \Longman\TelegramBot\Exception\TelegramLogException
     */
    public function testNewInstanceWithoutUpdatePath()
    {
        TelegramLog::initUpdateLog('');
    }

    public function testErrorStream()
    {
        $file = self::$logfiles['error'];
        $this->assertFileNotExists($file);
        TelegramLog::initErrorLog($file);
        TelegramLog::error('my error');
        TelegramLog::error('my 50% error');
        TelegramLog::error('my old %s %s error', 'custom', 'placeholder');
        TelegramLog::error('my new {place} {holder} error', ['place' => 'custom', 'holder' => 'placeholder']);
        $this->assertFileExists($file);
        $error_log = file_get_contents($file);
        $this->assertContains('bot_log.ERROR: my error', $error_log);
        $this->assertContains('bot_log.ERROR: my 50% error', $error_log);
        $this->assertContains('bot_log.ERROR: my old custom placeholder error', $error_log);
        $this->assertContains('bot_log.ERROR: my new custom placeholder error', $error_log);
    }

    public function testDebugStream()
    {
        $file = self::$logfiles['debug'];
        $this->assertFileNotExists($file);
        TelegramLog::initDebugLog($file);
        TelegramLog::debug('my debug');
        TelegramLog::debug('my 50% debug');
        TelegramLog::debug('my old %s %s debug', 'custom', 'placeholder');
        TelegramLog::debug('my new {place} {holder} debug', ['place' => 'custom', 'holder' => 'placeholder']);
        $this->assertFileExists($file);
        $debug_log = file_get_contents($file);
        $this->assertContains('bot_log.DEBUG: my debug', $debug_log);
        $this->assertContains('bot_log.DEBUG: my 50% debug', $debug_log);
        $this->assertContains('bot_log.DEBUG: my old custom placeholder debug', $debug_log);
        $this->assertContains('bot_log.DEBUG: my new custom placeholder debug', $debug_log);
    }

    public function testUpdateStream()
    {
        $file = self::$logfiles['update'];
        $this->assertFileNotExists($file);
        TelegramLog::initUpdateLog($file);
        TelegramLog::update('my update');
        TelegramLog::update('my 50% update');
        TelegramLog::update('my old %s %s update', 'custom', 'placeholder');
        TelegramLog::update('my new {place} {holder} update', ['place' => 'custom', 'holder' => 'placeholder']);
        $this->assertFileExists($file);
        $update_log = file_get_contents($file);
        $this->assertContains('my update', $update_log);
        $this->assertContains('my 50% update', $update_log);
        $this->assertContains('my old custom placeholder update', $update_log);
        $this->assertContains('my new custom placeholder update', $update_log);
    }

    public function testExternalStream()
    {
        $file = self::$logfiles['external'];
        $this->assertFileNotExists($file);

        $external_monolog = new Logger('bot_external_log');
        $external_monolog->pushHandler(new StreamHandler($file, Logger::ERROR));
        $external_monolog->pushHandler(new StreamHandler($file, Logger::DEBUG));

        TelegramLog::initialize($external_monolog);
        TelegramLog::error('my error');
        TelegramLog::error('my 50% error');
        TelegramLog::error('my old %s %s error', 'custom', 'placeholder');
        TelegramLog::error('my new {place} {holder} error', ['place' => 'custom', 'holder' => 'placeholder']);
        TelegramLog::debug('my debug');
        TelegramLog::debug('my 50% debug');
        TelegramLog::debug('my old %s %s debug', 'custom', 'placeholder');
        TelegramLog::debug('my new {place} {holder} debug', ['place' => 'custom', 'holder' => 'placeholder']);

        $this->assertFileExists($file);
        $file_contents = file_get_contents($file);
        $this->assertContains('bot_external_log.ERROR: my error', $file_contents);
        $this->assertContains('bot_external_log.ERROR: my 50% error', $file_contents);
        $this->assertContains('bot_external_log.ERROR: my old custom placeholder error', $file_contents);
        $this->assertContains('bot_external_log.ERROR: my new custom placeholder error', $file_contents);
        $this->assertContains('bot_external_log.DEBUG: my debug', $file_contents);
        $this->assertContains('bot_external_log.DEBUG: my 50% debug', $file_contents);
        $this->assertContains('bot_external_log.DEBUG: my old custom placeholder debug', $file_contents);
        $this->assertContains('bot_external_log.DEBUG: my new custom placeholder debug', $file_contents);
    }
}
