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

use Longman\TelegramBot\Exception\TelegramLogException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class TelegramLog
{
    /**
     * Monolog instance
     *
     * @var \Monolog\Logger
     */
    static protected $monolog;

    /**
     * Monolog instance for update
     *
     * @var \Monolog\Logger
     */
    static protected $monolog_update;

    /**
     * Path for error log
     *
     * @var string
     */
    static protected $error_log_path;

    /**
     * Path for debug log
     *
     * @var string
     */
    static protected $debug_log_path;

    /**
     * Path for update log
     *
     * @var string
     */
    static protected $update_log_path;

    /**
     * Temporary stream handle for debug log
     *
     * @var resource|null
     */
    static protected $debug_log_temp_stream_handle;

    /**
     * Initialize Monolog Logger instance, optionally passing an existing one
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
                    if (method_exists($handler, 'getLevel') && $handler->getLevel() === 400) {
                        self::$error_log_path = 'true';
                    }
                    if (method_exists($handler, 'getLevel') && $handler->getLevel() === 100) {
                        self::$debug_log_path = 'true';
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
     * @throws \Longman\TelegramBot\Exception\TelegramLogException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function initErrorLog($path)
    {
        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for error log');
        }
        self::initialize();
        self::$error_log_path = $path;

        return self::$monolog->pushHandler(
            (new StreamHandler(self::$error_log_path, Logger::ERROR))
                ->setFormatter(new LineFormatter(null, null, true))
        );
    }

    /**
     * Initialize debug log
     *
     * @param string $path
     *
     * @return \Monolog\Logger
     * @throws \Longman\TelegramBot\Exception\TelegramLogException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function initDebugLog($path)
    {
        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for debug log');
        }
        self::initialize();
        self::$debug_log_path = $path;

        return self::$monolog->pushHandler(
            (new StreamHandler(self::$debug_log_path, Logger::DEBUG))
                ->setFormatter(new LineFormatter(null, null, true))
        );
    }

    /**
     * Get the stream handle of the temporary debug output
     *
     * @return mixed The stream if debug is active, else false
     */
    public static function getDebugLogTempStream()
    {
        if (self::$debug_log_temp_stream_handle === null) {
            if (!self::isDebugLogActive()) {
                return false;
            }
            self::$debug_log_temp_stream_handle = fopen('php://temp', 'w+b');
        }

        return self::$debug_log_temp_stream_handle;
    }

    /**
     * Write the temporary debug stream to log and close the stream handle
     *
     * @param string $message Message (with placeholder) to write to the debug log
     */
    public static function endDebugLogTempStream($message = '%s')
    {
        if (is_resource(self::$debug_log_temp_stream_handle)) {
            rewind(self::$debug_log_temp_stream_handle);
            self::debug($message, stream_get_contents(self::$debug_log_temp_stream_handle));
            fclose(self::$debug_log_temp_stream_handle);
            self::$debug_log_temp_stream_handle = null;
        }
    }

    /**
     * Initialize update log
     *
     * @param string $path
     *
     * @return \Monolog\Logger
     * @throws \Longman\TelegramBot\Exception\TelegramLogException
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public static function initUpdateLog($path)
    {
        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for update log');
        }
        self::$update_log_path = $path;

        if (self::$monolog_update === null) {
            self::$monolog_update = new Logger('bot_update_log');

            self::$monolog_update->pushHandler(
                (new StreamHandler(self::$update_log_path, Logger::INFO))
                    ->setFormatter(new LineFormatter('%message%' . PHP_EOL))
            );
        }

        return self::$monolog_update;
    }

    /**
     * Is error log active
     *
     * @return bool
     */
    public static function isErrorLogActive()
    {
        return self::$error_log_path !== null;
    }

    /**
     * Is debug log active
     *
     * @return bool
     */
    public static function isDebugLogActive()
    {
        return self::$debug_log_path !== null;
    }

    /**
     * Is update log active
     *
     * @return bool
     */
    public static function isUpdateLogActive()
    {
        return self::$update_log_path !== null;
    }

    /**
     * Report error log
     *
     * @param string $text
     */
    public static function error($text)
    {
        if (self::isErrorLogActive()) {
            $text = self::getLogText($text, func_get_args());
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
            $text = self::getLogText($text, func_get_args());
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
            $text = self::getLogText($text, func_get_args());
            self::$monolog_update->info($text);
        }
    }

    /**
     * Applies vsprintf to the text if placeholder replacements are passed along.
     *
     * @param string $text
     * @param array  $args
     *
     * @return string
     */
    protected static function getLogText($text, array $args = [])
    {
        // Pop the $text off the array, as it gets passed via func_get_args().
        array_shift($args);

        // If no placeholders have been passed, don't parse the text.
        if (empty($args)) {
            return $text;
        }

        return vsprintf($text, $args);
    }
}
