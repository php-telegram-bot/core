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

use Longman\TelegramBot\Exception\TelegramLogException;
use Longman\TelegramBot\TelegramLog;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Tests\TestHelpers;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class TelegramLogTest extends TestCase
{
    /**
     * Logfile paths
     */
    private $logfiles = [
        'error'    => '/tmp/errorlog.log',
        'debug'    => '/tmp/debuglog.log',
        'update'   => '/tmp/updatelog.log',
        'external' => '/tmp/externallog.log',
    ];

    /**
     * setUp
     */
    protected function setUp()
    {
        // Make sure no monolog instance is set before each test.
        TestHelpers::setStaticProperty('Longman\TelegramBot\TelegramLog', 'monolog', null);
    }

    /**
     * tearDown
     */
    protected function tearDown()
    {
        // Make sure no logfiles exist.
        foreach ($this->logfiles as $file) {
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
        $file = $this->logfiles['error'];
        $this->assertFalse(file_exists($file));
        TelegramLog::initErrorLog($file);
        TelegramLog::error('my error');
        $this->assertTrue(file_exists($file));
        $this->assertContains('bot_log.ERROR: my error', file_get_contents($file));
    }

    public function testDebugStream()
    {
        $file = $this->logfiles['debug'];
        $this->assertFalse(file_exists($file));
        TelegramLog::initDebugLog($file);
        TelegramLog::debug('my debug');
        $this->assertTrue(file_exists($file));
        $this->assertContains('bot_log.DEBUG: my debug', file_get_contents($file));
    }

    public function testUpdateStream()
    {
        $file = $this->logfiles['update'];
        $this->assertFalse(file_exists($file));
        TelegramLog::initUpdateLog($file);
        TelegramLog::update('my update');
        $this->assertTrue(file_exists($file));
        $this->assertContains('my update', file_get_contents($file));
    }

    public function testExternalStream()
    {
        $file = $this->logfiles['external'];
        $this->assertFalse(file_exists($file));

        $external_monolog = new Logger('bot_update_log');
        $external_monolog->pushHandler(new StreamHandler($file, Logger::ERROR));
        $external_monolog->pushHandler(new StreamHandler($file, Logger::DEBUG));

        TelegramLog::initialize($external_monolog);
        TelegramLog::error('my error');
        TelegramLog::debug('my debug');

        $this->assertTrue(file_exists($file));
        $file_contents = file_get_contents($file);
        $this->assertContains('bot_update_log.ERROR: my error', $file_contents);
        $this->assertContains('bot_update_log.DEBUG: my debug', $file_contents);
    }
}
