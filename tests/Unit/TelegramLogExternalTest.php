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

use Longman\TelegramBot\TelegramLog;

use Longman\TelegramBot\Exception\TelegramLogException;
/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class TelegramLogExternalTest extends TestCase
{

    /**
    * setUp
    */
    protected function setUp()
    {
    }

    /**
     * @test
     */
    public function testExtenalStream()
    {
        $file = '/tmp/externallog.log';
        $this->assertFalse(file_exists($file));

        $external_monolog = new \Monolog\Logger('bot_update_log');
        $update_handler = new \Monolog\Handler\StreamHandler($file, \Monolog\Logger::ERROR);
        $info_handler = new \Monolog\Handler\StreamHandler($file, \Monolog\Logger::INFO);
        $external_monolog->pushHandler($update_handler);
        $external_monolog->pushHandler($info_handler);

        TelegramLog::initialize($external_monolog);
        TelegramLog::error('my error');
        $this->assertTrue(file_exists($file));
        unlink($file);
    }
}
