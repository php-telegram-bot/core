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

/**
 * @package         TelegramTest
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class AudioTest extends TestCase
{
    /**
     * @var array
     */
    private $record;

    public function setUp()
    {
        $this->record = TestHelpers::getFakeRecordedAudio();
    }

    public function testInstance()
    {
        $audio = new Audio($this->record);
        self::assertInstanceOf('Longman\TelegramBot\Entities\Audio', $audio);
    }

    public function testGetProperties()
    {
        $audio = new Audio($this->record);
        self::assertEquals($this->record['file_id'], $audio->getFileId());
        self::assertEquals($this->record['duration'], $audio->getDuration());
        self::assertEquals($this->record['performer'], $audio->getPerformer());
        self::assertEquals($this->record['title'], $audio->getTitle());
        self::assertEquals($this->record['mime_type'], $audio->getMimeType());
        self::assertEquals($this->record['file_size'], $audio->getFileSize());
    }
}
