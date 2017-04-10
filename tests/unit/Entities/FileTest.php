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
    * lat, long data
    *
    * @var array
    *
    */
    public $data;

   /**
    *
    * Set Up
    *
    */
    public function setUp()
    {
        $this->data = [
            'file_id'    => (int)mt_rand(1, 99),
            'file_size'  => (int)mt_rand(100, 99999),
            'file_path'  => 'home' . DIRECTORY_SEPARATOR . 'phpunit'
        ];
    }

   /**
    *
    * TearDown 
    *
    */
    public function tearDown()
    {
        //pass
    }

   /**
    *
    * Testing base stage with data object creating
    *
    */
    public function testBaseStageLocation()
    {
        $file = new File($this->data);
        $this->assertInstanceOf('Longman\TelegramBot\Entities\File', $file);
    }

    /**
    *
    * Testing getFileId
    *
    */
    public function testGetFileId()
    {
        $file = new File($this->data);
        $id = $file->getFileId();
        $this->assertInternalType('int', $id);
        $this->assertEquals($this->data['file_id'], $id);
    }
    
   /**
    *
    * Testing getFileSize
    *
    */
    public function testGetFileSize()
    {
        $file = new File($this->data);
        $size = $file->getFileSize();
        $this->assertInternalType('int', $size);
        $this->assertEquals($this->data['file_size'], $size);
    }

   /**
    *
    * Testing getFilePath
    *
    */
    public function testGetFilePath()
    {
        $file = new File($this->data);
        $path = $file->getFilePath();
        $this->assertEquals($this->data['file_path'], $path);
    }

    /**
    * 
    * Testing getFileSize without data
    *
    */
    public function testGetFileSizeWithoutData()
    {
        unset($this->data['file_size']);
        $file = new File($this->data);
        $id = $file->getFileSize();
        $this->assertNull($id);
    }

    /**
    *
    * Testing getFilePath without data
    *
    */
    public function testGetFilePathWithoutData()
    {
        unset($this->data['file_path']);
        $file = new File($this->data);
        $path = $file->getFilePath();
        $this->assertNull($path);
    }
}
