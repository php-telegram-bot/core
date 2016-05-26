<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Longman\TelegramBot\Exception\TelegramLogException;

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
     * Path for error log
     *
     * @var string
     */
    static protected $error_log_path = null;

    /**
     * Path for debug log
     *
     * @var string
     */
    static protected $debug_log_path = null;

    /**
     * Path for update log
     *
     * @var string
     */
    static protected $update_log_path = null;

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
    public static function initialize(Logger $external_monolog = null)
    {
        if (self::$monolog === null) {
            if ($external_monolog !== null) {
                self::$monolog = $external_monolog;

                foreach (self::$monolog->getHandlers() as $handler) {
                    if ($handler->getLevel() == 400 ) {
                        self::$error_log_path = true;
                    }
                    if ($handler->getLevel() == 100 ) {
                        self::$debug_log_path = true;
                    }
                }
            } else {
                self::$monolog = new Logger('bot_log');
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
        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for error log');
        }
        self::initialize();
        self::$error_log_path = $path;
        return self::$monolog->pushHandler(new StreamHandler(self::$error_log_path, Logger::ERROR));
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
        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for debug log');
        }
        self::initialize();
        self::$debug_log_path = $path;
        return self::$monolog->pushHandler(new StreamHandler(self::$debug_log_path, Logger::DEBUG));
    }

    /**
     * Initialize update log
     *
     * Initilize monolog instance. Singleton
     * Is possbile provide an external monolog instance
     *
     * @param string $path
     *
     * @return \Monolog\Logger
     */
    public static function initUpdateLog($path)
    {
        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for update log');
        }
        self::$update_log_path = $path;
        if (self::$monolog_update === null) {
            self::$monolog_update = new Logger('bot_update_log');
            // Create a formatter
            $output = "%message%\n";
            $formatter = new LineFormatter($output);

            // Update handler
            $update_handler = new StreamHandler(self::$update_log_path, Logger::INFO);
            $update_handler->setFormatter($formatter);

            self::$monolog_update->pushHandler($update_handler);
        }
        return self::$monolog;
    }

    /**
     * Is error log active
     *
     * @return bool
     */
    public static function isErrorLogActive()
    {
        return (self::$error_log_path !== null);
    }

    /**
     * Is debug log active
     *
     * @return bool
     */
    public static function isDebugLogActive()
    {
        return (self::$debug_log_path !== null);
    }

    /**
     * Is update log active
     *
     * @return bool
     */
    public static function isUpdateLogActive()
    {
        return (self::$update_log_path !== null);
    }

    /**
     * Report error log
     *
     * @param string $text
     */
    public static function error($text)
    {
        if (self::isErrorLogActive()) {
            self::$monolog->error($text);
        }
    }

    /**
     * Report debug log
     *
     * @param string $text
     */
    public static function debug($text)
    {
        if (self::isDebugLogActive()) {
            self::$monolog->debug($text);
        }
    }

    /**
     * Report update log
     *
     * @param string $text
     */
    public static function update($text)
    {
        if (self::isUpdateLogActive()) {
            self::$monolog_update->info($text);
        }
    }
}
