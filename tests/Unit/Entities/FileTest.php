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

use Longman\TelegramBot\Entities\File;

/**
 * @package         TelegramTest
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class FileTest extends TestCase
{
    /**
     * @var array
     */
    private $data;

    public function setUp(): void
    {
        $this->data = [
            'file_id'   => (int) mt_rand(1, 99),
            'file_size' => (int) mt_rand(100, 99999),
            'file_path' => 'home' . DIRECTORY_SEPARATOR . 'phpunit',
        ];
    }

    public function testBaseStageLocation(): void
    {
        $file = new File($this->data);
        self::assertInstanceOf(File::class, $file);
    }

    public function testGetFileId(): void
    {
        $file = new File($this->data);
        $id   = $file->getFileId();
        self::assertIsInt($id);
        self::assertEquals($this->data['file_id'], $id);
    }

    public function testGetFileSize(): void
    {
        $file = new File($this->data);
        $size = $file->getFileSize();
        self::assertIsInt($size);
        self::assertEquals($this->data['file_size'], $size);
    }

    public function testGetFilePath(): void
    {
        $file = new File($this->data);
        $path = $file->getFilePath();
        self::assertEquals($this->data['file_path'], $path);
    }

    public function testGetFileSizeWithoutData(): void
    {
        unset($this->data['file_size']);
        $file = new File($this->data);
        $id   = $file->getFileSize();
        self::assertNull($id);
    }

    public function testGetFilePathWithoutData(): void
    {
        unset($this->data['file_path']);
        $file = new File($this->data);
        $path = $file->getFilePath();
        self::assertNull($path);
    }
}
