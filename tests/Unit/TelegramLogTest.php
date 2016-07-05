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
     * @var array Dummy logfile paths
     */
    private $logfiles = [
        'error'    => '/tmp/php-telegram-bot-errorlog.log',
        'debug'    => '/tmp/php-telegram-bot-debuglog.log',
        'update'   => '/tmp/php-telegram-bot-updatelog.log',
        'external' => '/tmp/php-telegram-bot-externallog.log',
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
        $this->assertFileNotExists($file);
        TelegramLog::initErrorLog($file);
        TelegramLog::error('my error');
        $this->assertFileExists($file);
        $this->assertContains('bot_log.ERROR: my error', file_get_contents($file));
    }

    public function testDebugStream()
    {
        $file = $this->logfiles['debug'];
        $this->assertFileNotExists($file);
        TelegramLog::initDebugLog($file);
        TelegramLog::debug('my debug');
        $this->assertFileExists($file);
        $this->assertContains('bot_log.DEBUG: my debug', file_get_contents($file));
    }

    public function testUpdateStream()
    {
        $file = $this->logfiles['update'];
        $this->assertFileNotExists($file);
        TelegramLog::initUpdateLog($file);
        TelegramLog::update('my update');
        $this->assertFileExists($file);
        $this->assertContains('my update', file_get_contents($file));
    }

    public function testExternalStream()
    {
        $file = $this->logfiles['external'];
        $this->assertFileNotExists($file);

        $external_monolog = new Logger('bot_update_log');
        $external_monolog->pushHandler(new StreamHandler($file, Logger::ERROR));
        $external_monolog->pushHandler(new StreamHandler($file, Logger::DEBUG));

        TelegramLog::initialize($external_monolog);
        TelegramLog::error('my error');
        TelegramLog::debug('my debug');

        $this->assertFileExists($file);
        $file_contents = file_get_contents($file);
        $this->assertContains('bot_update_log.ERROR: my error', $file_contents);
        $this->assertContains('bot_update_log.DEBUG: my debug', $file_contents);
    }
}
