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

use Longman\TelegramBot\Config;

class ConfigTest extends TestCase
{
    public function testAddCommandsPathsBefore()
    {
        $config = new Config();

        $paths = [
            '/tmp/php-telegram-bot-custom-commands-config-1',
            '/tmp/php-telegram-bot-custom-commands-config-2',
            '/tmp/php-telegram-bot-custom-commands-config-3',
        ];
        foreach ($paths as $path) {
            mkdir($path);
        }

        $config->addCommandsPaths($paths);
        foreach ($paths as $path) {
            rmdir($path);
        }

        $this->assertEquals(array_reverse($paths), $config->getCommandsPaths());
    }

    public function testAddCommandsPathsAfter()
    {
        $config = new Config();

        $paths = [
            '/tmp/php-telegram-bot-custom-commands-config-1',
            '/tmp/php-telegram-bot-custom-commands-config-2',
            '/tmp/php-telegram-bot-custom-commands-config-3',
        ];
        foreach ($paths as $path) {
            mkdir($path);
        }

        $config->addCommandsPaths($paths, false);
        foreach ($paths as $path) {
            rmdir($path);
        }

        $this->assertEquals($paths, $config->getCommandsPaths());
    }

    public function testAdmins()
    {
        $config = new Config();

        $admins = [1, 2, 3];

        $config->addAdmins($admins);

        $this->assertEquals($admins, $config->getAdmins());
    }

    public function testUploadPath()
    {
        $config = new Config();

        $path = '/some/path';

        $config->setUploadPath($path);

        $this->assertEquals($path, $config->getUploadPath());
    }

    public function testDownloadPath()
    {
        $config = new Config();

        $path = '/some/path';

        $config->setDownloadPath($path);

        $this->assertEquals($path, $config->getDownloadPath());
    }
}
