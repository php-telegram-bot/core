<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

namespace Longman\TelegramBot;

/**
 * Class TelegramLog.
 */
class TelegramLog
{
    /**
     * Monolog instance
     *
     * @var \Monolog\Logger
     */
    static protected $monolog = null;

    /**
     * Monolog instance for update
     *
     * @var \Monolog\Logger
     */
    static protected $monolog_update = null;

    /**
     * Initialize
     *
     * Initilize monolog instance. Singleton
     * Is possbile provide an external monolog instance
     *
     * @param \Monolog\Logger
     *
     * @return \Monolog\Logger
     */
    public static function initialize(\Monolog\Logger $external_monolog = null)
    {
        if (self::$monolog === null) {
            if ($external_monolog !== null) {
                self::$monolog = $external_monolog;
            } else {
                self::$monolog = new \Monolog\Logger('bot_log');
            }
        }
        return self::$monolog;
    }

    /**
     * Initialize error log
     *
     * @param string $path
     *
     * @return \Monolog\Logger
     */
    public static function initErrorLog($path)
    {
        self::initialize();
        return self::$monolog->pushHandler(new \Monolog\Handler\StreamHandler($path, \Monolog\Logger::ERROR));
    }

    /**
     * Initialize debug log
     *
     * @param string $path
     *
     * @return \Monolog\Logger
     */
    public static function initDebugLog($path)
    {
        self::initialize();
        return self::$monolog->pushHandler(new \Monolog\Handler\StreamHandler($path, \Monolog\Logger::DEBUG));
    }

    /**
     * Initialize update log
     *
     * Initilize monolog instance. Singleton
     * Is possbile provide an external monolog instance
     *
     * @return \Monolog\Logger
     */
    public static function initUpdateLog()
    {
        if (self::$monolog_update === null) {
            self::$monolog_update = new \Monolog\Logger('bot_update_log');
            // Create a formatter
            $formatter = new \Monolog\Formatter\LineFormatter('%message%');
            // Update handler
            $update_handler = new \Monolog\Handler\StreamHandler($path, \Monolog\Logger::INFO);
            $update_handler->setFormatter($formatter);

            self::$monolog_update->pushHandler($update_handler);
        }
        return self::$monolog;
    }

    /**
     * Report error log
     *
     * @param string $text
     */
    public static function error($text)
    {
        self::$monolog->error($text);
    }

    /**
     * Report debug log
     *
     * @param string $text
     */
    public static function debug($text)
    {
        self::$monolog->debug($text);
    }

    /**
     * Report update log
     *
     * @param string $text
     */
    public static function update($text)
    {
        self::$monolog_update->info($text);
    }
}
