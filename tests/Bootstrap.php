<?php
/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests;
/*
 * Set error reporting to the max level.
 */
error_reporting(-1);

/*
 * Set UTC timezone.
 */
date_default_timezone_set('UTC');


$root = realpath(dirname(dirname(__FILE__)));

/**
 * Check that --dev composer installation was done
 */
if (!file_exists($root . '/vendor/autoload.php')) {
    throw new Exception(
        'Please run "php composer.phar install --dev" in root directory '
        . 'to setup unit test dependencies before running the tests'
    );
}

// Include the Composer autoloader
$loader = require __DIR__ . '/../vendor/autoload.php';


/*
 * Unset global variables that are no longer needed.
 */
unset($root, $loader);
