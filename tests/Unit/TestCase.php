<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function skip64BitTest()
    {
        if (PHP_INT_SIZE == 4) {
            $this->markTestSkipped(
                'Skipping test that can run only on a 64-bit build of PHP.'
            );
        }
    }
}
