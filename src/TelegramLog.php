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
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class TelegramLog
{
    /**
     * Monolog instance
     *
     * @var Logger
     */
    static protected $monolog;

    /**
     * Monolog instance for update
     *
     * @var Logger
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
     * Initialize
     *
     * Initilize monolog instance. Singleton
     * Is possbile provide an external monolog instance
     *
     * @param Logger $external_monolog
     *
     * @return Logger
     */
    public static function initialize(Logger $external_monolog = null)
    {
        if (self::$monolog === null) {
            if ($external_monolog !== null) {
                self::$monolog = $external_monolog;
            } else {
                self::$monolog = new Logger('bot_log');
            }
        }

        return self::$monolog;
    }

    /**
     * Initialize update
     *
     * Initilize monolog update instance. Singleton
     * Is possbile provide an external monolog instance
     *
     * @param Logger $external_monolog
     *
     * @return Logger
     */
    public static function initializeUpdate(Logger $external_monolog = null)
    {
        if (self::$monolog_update === null) {
            if ($external_monolog !== null) {
                self::$monolog_update = $external_monolog;
            } else {
                self::$monolog_update = new Logger('bot_update_log');
            }
        }

        return self::$monolog_update;
    }

    /**
     * Initialize error log
     *
     * @param string           $path
     * @param HandlerInterface $external_handler
     *
     * @return Logger
     * @throws TelegramLogException
     */
    public static function initErrorLog($path, HandlerInterface $external_handler = null)
    {
        if (($path === null || $path === '') && is_null($external_handler)) {
            throw new TelegramLogException('Empty path for error log');
        }
        self::initialize();

        if (is_null($external_handler)) {
            self::$error_log_path = $path;
            $handler = new StreamHandler(self::$error_log_path, Logger::ERROR);
        } else {
            self::$error_log_path = 'true';
            $handler = $external_handler;
        }

        return self::$monolog->pushHandler($handler->setFormatter(new LineFormatter(null, null, true)));
    }

    /**
     * Initialize debug log
     *
     * @param string           $path
     * @param HandlerInterface $external_handler
     *
     * @return Logger
     * @throws TelegramLogException
     */
    public static function initDebugLog($path, HandlerInterface $external_handler = null)
    {
        if (($path === null || $path === '') && is_null($external_handler)) {
            throw new TelegramLogException('Empty path for debug log');
        }
        self::initialize();

        if (is_null($external_handler)) {
            self::$debug_log_path = $path;
            $handler = new StreamHandler(self::$debug_log_path, Logger::DEBUG);
        } else {
            self::$debug_log_path = 'true';
            $handler = $external_handler;
        }

        return self::$monolog->pushHandler($handler->setFormatter(new LineFormatter(null, null, true)));
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
     * Initilize monolog instance. Singleton
     * Is possbile provide an external monolog instance
     *
     * @param string           $path
     * @param HandlerInterface $external_handler
     *
     * @return Logger
     * @throws TelegramLogException
     */
    public static function initUpdateLog($path, HandlerInterface $external_handler = null)
    {
        if (($path === null || $path === '') && is_null($external_handler)) {
            throw new TelegramLogException('Empty path for update log');
        }
        self::initializeUpdate();

        if (is_null($external_handler)) {
            self::$update_log_path = $path;
            $handler = new StreamHandler(self::$update_log_path, Logger::INFO);
        } else {
            self::$update_log_path = 'true';
            $handler = $external_handler;
        }

        return self::$monolog_update->pushHandler($handler->setFormatter(new LineFormatter('%message%' . PHP_EOL)));
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

        // Suppress warning if placeholders don't match out.
        return @vsprintf($text, $args) ?: $text;
    }
}
