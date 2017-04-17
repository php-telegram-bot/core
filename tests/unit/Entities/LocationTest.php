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

/**
 * @package         TelegramTest
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            https://github.com/php-telegram-bot/core
 */
class LocationTest extends TestCase
{
    private $coordinates;

    public function setUp()
    {
        $this->coordinates = [
            'longitude' => (float) mt_rand(10, 69),
            'latitude'  => (float) mt_rand(10, 48),
        ];
    }

    public function testBaseStageLocation()
    {
        $location = new Location($this->coordinates);
        $this->assertInstanceOf('Longman\TelegramBot\Entities\Location', $location);
    }

    public function testGetLongitude()
    {
        $location = new Location($this->coordinates);
        $long     = $location->getLongitude();
        $this->assertInternalType('float', $long);
        $this->assertEquals($this->coordinates['longitude'], $long);
    }

    public function testGetLatitude()
    {
        $location = new Location($this->coordinates);
        $lat      = $location->getLatitude();
        $this->assertInternalType('float', $lat);
        $this->assertEquals($this->coordinates['latitude'], $lat);
    }
}
