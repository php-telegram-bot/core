<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit\Entities;

use Longman\TelegramBot\Entities\Location;
use Longman\TelegramBot\Tests\Unit\TestCase;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Baev Nikolay <gametester3d@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
 */
class LocationTest extends TestCase
{
    private $coordinates;

    public function setUp(): void
    {
        $this->coordinates = [
            'longitude' => (float) mt_rand(10, 69),
            'latitude'  => (float) mt_rand(10, 48),
        ];
    }

    public function testBaseStageLocation(): void
    {
        $location = new Location($this->coordinates);
        self::assertInstanceOf(Location::class, $location);
    }

    public function testGetLongitude(): void
    {
        $location = new Location($this->coordinates);
        $long     = $location->getLongitude();
        self::assertIsFloat($long);
        self::assertEquals($this->coordinates['longitude'], $long);
    }

    public function testGetLatitude(): void
    {
        $location = new Location($this->coordinates);
        $lat      = $location->getLatitude();
        self::assertIsFloat($lat);
        self::assertEquals($this->coordinates['latitude'], $lat);
    }
}
