<?php

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
