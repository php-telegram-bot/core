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

use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @package         UserTest
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class UserTest extends TestCase
{
    /**
    * setUp
    *
    */
    public function setUp()
    {
        //void
    }

    /**
    * tearDown
    *
    */
    public function tearDown()
    {
        //void
    }

    /**
    * Test base stage
    *
    */
    public function testStageBase()
    {
        $user = new User(['id' => mt_rand(1, 99)]);
        $this->assertInstanceOf('Longman\TelegramBot\Entities\User', $user);
    }

    /**
    * Test sage without param user id 
    *
    * @expectedException Longman\TelegramBot\Exception\TelegramException
    *
    */
    public function testStageWithoutId()
    {
        new User([]);
    }

    /**
    * Test stage get user id
    *
    */
    public function testGetId()
    {
        $user = new User(['id' => mt_rand(1, 99)]);
        $result = $user->getId();
        $this->assertGreaterThan(0, $result);
    }

    /**
    * Test stage get first name
    *
    */
    public function testGetFirstName()
    {
        $user = new User(['id' => mt_rand(1, 99), 'first_name' => 'name_phpunit']);
        $result = $user->getFirstName();
        $this->assertEquals('name_phpunit', $result);
    }

    /**
    * Test stage get last name
    *
    */
    public function testGetLastName()
    {
        $user = new User(['id' => mt_rand(1, 99), 'last_name' => 'name_phpunit']);
        $result = $user->getLastName();
        $this->assertEquals('name_phpunit', $result);
    }

    /**
    * Test stage get username
    *
    */
    public function testGetUsername()
    {
        $user = new User(['id' => mt_rand(1, 99), 'username' => 'name_phpunit']);
        $result = $user->getUsername();
        $this->assertEquals('name_phpunit', $result);
    }

    /**
    * Test stage mention user
    *
    */
    public function testTryMention()
    {
        $user = new User(['id' => mt_rand(1, 99), 'username' => 'name_phpunit']);
        $result = $user->tryMention();
        $this->assertRegExp('/^\@.*/', $result);
    }

    /**
    * Test stage mention user without param username
    *
    */
    public function testTryMentionWithoutUsernameStageOne()
    {
        $user = new User(['id' => mt_rand(1, 99), 'first_name' => 'name_phpunit']);
        $result = $user->tryMention();
        $this->assertEquals('name_phpunit', $result);
    }

    /**
    * Test stage mention user without username but with last and first name
    *
    */
    public function testTryMentionWithoutUsernameStageTwo()
    {
        $user = new User(['id' => mt_rand(1, 99), 'first_name' => 'name_phpunit', 
            'last_name' => 'name_phpunit']);
        $result = $user->tryMention();
        $this->assertRegExp('/^.*\s{1}.*$/', $result);
    }
   
}
