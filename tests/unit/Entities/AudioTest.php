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

use Longman\TelegramBot\Entities\Audio;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Tests\Unit\TestHelpers;

/**
 * @package         TelegramTest
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class AudioTest extends TestCase
{
    /**
    * @var array
    */
    private $record;
    /**
    * Set Up
    */
    public function setUp()
    {
        $this->record = TestHelpers::getFakeRecordedAudio();
    }
    /**
    * Testing base stage with data object creating
    */

    public function testBaseStageAudio()
    {
        $audio = new Audio($this->record);
        $this->assertInstanceOf('Longman\TelegramBot\Entities\Audio', $audio);
    }
    
    /**
    * Test base stage without duration property
    *
    * @expectedException Longman\TelegramBot\Exception\TelegramException
    */
    public function testBaseStageWithoutDuration()
    {
        $this->record['duration'] = null;
        new Audio($this->record);
    }

   /**
    * Test base stage without file_id property
    *
    * @expectedException Longman\TelegramBot\Exception\TelegramException
    */
    public function testBaseStageWithoutFileId()
    {
        $this->record['file_id'] = null;
        new Audio($this->record);
    } 

    /**
    * Test get file id
    */
    public function testGetFileId()
    {
        $audio = new Audio($this->record);
        $file_id = $audio->getFileId();
        $this->assertEquals($this->record['file_id'], $file_id);
    }

    /**
    * Test get duration track
    */
    public function testGetDuration()
    {
        $audio = new Audio($this->record);
        $duration = $audio->getDuration();
        $this->assertEquals($this->record['duration'], $duration);
    }

    /**
    * Test get performer track
    */
    public function testGetPerformer()
    {
        $audio = new Audio($this->record);
        $performer = $audio->getPerformer();
        $this->assertEquals($this->record['performer'], $performer);
    }

    /**
    * Test get title track
    */
    public function testGetTitle()
    {
        $audio = new Audio($this->record);
        $title = $audio->getTitle();
        $this->assertEquals($this->record['title'], $title);
    }
    /**
    * Test get mime type file
    */
    public function testGetMimeType()
    {
        $audio = new Audio($this->record);
        $mime_type = $audio->getMimeType();
        $this->assertEquals($this->record['mime_type'], $mime_type);
    }
    /**
    * Test get file size 
    */
    public function testGetFileSize()
    {
        $audio = new Audio($this->record);
        $file_size = $audio->getFileSize();
        $this->assertEquals($this->record['file_size'], $file_size);
    }
}
