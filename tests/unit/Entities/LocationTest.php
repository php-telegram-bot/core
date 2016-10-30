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

use Longman\TelegramBot\Entities\Location;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Tests\Unit\TestHelpers;

/**
 * @package         TelegramTest
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class LocationTest extends TestCase
{
    
   /**
    * lat, long data
    *
    * @var array
    *
    */
    public $coordinates;

   /**
    *
    * Set Up
    *
    */
    public function setUp()
    {
        $this->coordinates = [
            'longitude' => (float)mt_rand(10, 69),
            'latitude'  => (float)mt_rand(10, 48)           
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
        $location = new Location($this->coordinates);
        $this->assertInstanceOf('Longman\TelegramBot\Entities\Location', $location);
    }

    /**
    *
    * Testing getLongitude
    *
    */
    public function testGetLongitude()
    {
        $location = new Location($this->coordinates);
        $long = $location->getLongitude();
        $this->assertInternalType('float', $long);
        $this->assertEquals($this->coordinates['longitude'], $long);
    }
    
   /**
    *
    * Testing getLatitude
    *
    */
    public function testGetLatitude()
    {
        $location = new Location($this->coordinates);
        $lat = $location->getLatitude();
        $this->assertInternalType('float', $lat);
        $this->assertEquals($this->coordinates['latitude'], $lat);
    }

   /**
    *
    * Testing getLongitude without longitude
    *
    * @expectedException Longman\TelegramBot\Exception\TelegramException
    * 
    */
    public function testGetLongitudeWithoutLongitude()
    {
        unset($this->coordinates['longitude']);
        new Location($this->coordinates);
    }

   /**
    *
    * Testing getLongitude without latitude
    *
    * @expectedException Longman\TelegramBot\Exception\TelegramException
    * 
    */
    public function testGetLongitudeWithoutLatitude()
    {
        unset($this->coordinates['latitude']);
        new Location($this->coordinates);
    }
}
