<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit\Commands;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Tests\Unit\TestCase;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class CommandTestCase extends TestCase
{
    /**
     * @var \Longman\TelegramBot\Telegram
     */
    protected $telegram;

    /**
     * @var \Longman\TelegramBot\Commands\Command
     */
    protected $command;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->telegram = new Telegram(self::$dummy_api_key, 'testbot');

        // Add custom commands dedicated to do some tests.
        $this->telegram->addCommandsPath(__DIR__ . '/CustomTestCommands');
        $this->telegram->getCommandsList();
    }

    /**
     * Make sure the version number is in the format x.x.x, x.x or x
     */
    public function testVersionNumberFormat()
    {
        $this->assertRegExp('/^(\d+\\.)?(\d+\\.)?(\d+)$/', $this->command->getVersion());
    }
}
