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

use \Longman\TelegramBot\Entities\User;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class UserTest extends TestCase
{
    /**
    * @var \Longman\TelegramBot\Entities\User
    */
    private $user;

    public function testUsername()
    {
        $this->user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => 'jtaylor']);
        $this->assertEquals('@jtaylor', $this->user->tryMention());
    }

    public function testFirstName()
    {
        $this->user = new User(['id' => 1, 'first_name' => 'John']);
        $this->assertEquals('John', $this->user->tryMention());
    }

    public function testLastName()
    {
        $this->user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor']);
        $this->assertEquals('John Taylor', $this->user->tryMention());
    }

    public function testStripMarkDown()
    {
        $this->user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor']);
        $this->assertEquals('\`\[\*\_', $this->user->stripMarkDown('`[*_'));
    }

    public function testPrependAt()
    {
        $this->user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor']);
        $this->assertEquals('@string', $this->user->prependAt('string'));
    }

    public function testUsernameMarkdown()
    {
        $this->user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => 'Taylor', 'username' => 'j_taylor']);
        $this->assertEquals('@j_taylor', $this->user->tryMention());
        $this->assertEquals('@j\_taylor', $this->user->tryMention(true));
    }

    public function testFirstNameMarkdown()
    {
        $this->user = new User(['id' => 1, 'first_name' => 'John[']);
        $this->assertEquals('John[', $this->user->tryMention());
        $this->assertEquals('John\[', $this->user->tryMention(true));
    }

    public function testLastNameMarkdown()
    {
        $this->user = new User(['id' => 1, 'first_name' => 'John', 'last_name' => '`Taylor`']);
        $this->assertEquals('John `Taylor`', $this->user->tryMention());
        $this->assertEquals('John \`Taylor\`', $this->user->tryMention(true));
    }
}
