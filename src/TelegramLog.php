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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class TelegramLog
 *
 * @method static void emergency(string $message, array $context = [])
 * @method static void alert(string $message, array $context = [])
 * @method static void critical(string $message, array $context = [])
 * @method static void error(string $message, array $context = [])
 * @method static void warning(string $message, array $context = [])
 * @method static void notice(string $message, array $context = [])
 * @method static void info(string $message, array $context = [])
 * @method static void debug(string $message, array $context = [])
 * @method static void update(string $message, array $context = [])
 */
class TelegramLog
{
    /**
     * Logger instance
     *
     * @var LoggerInterface
     */
    protected static $logger;

    /**
     * Logger instance for update
     *
     * @var LoggerInterface
     */
    protected static $update_logger;

    /**
     * Always log the request and response data to the debug log, also for successful requests
     *
     * @var bool
     */
    public static $always_log_request_and_response = false;

    /**
     * Temporary stream handle for debug log
     *
     * @var resource|null
     */
    protected static $debug_log_temp_stream_handle;

    /**
     * Remove bot token from debug stream
     *
     * @var bool
     */
    public static $remove_bot_token = true;

    /**
     * Initialise logging.
     *
     * @param LoggerInterface|null $logger
     * @param LoggerInterface|null $update_logger
     */
    public static function initialize(LoggerInterface $logger = null, LoggerInterface $update_logger = null): void
    {
        self::$logger        = $logger ?: new NullLogger();
        self::$update_logger = $update_logger ?: new NullLogger();
    }

    /**
     * Get the stream handle of the temporary debug output
     *
     * @return mixed The stream if debug is active, else false
     */
    public static function getDebugLogTempStream()
    {
        if ((self::$debug_log_temp_stream_handle === null) && $temp_stream_handle = fopen('php://temp', 'wb+')) {
            self::$debug_log_temp_stream_handle = $temp_stream_handle;
        }

        return self::$debug_log_temp_stream_handle;
    }

    /**
     * Write the temporary debug stream to log and close the stream handle
     *
     * @param string $message Message (with placeholder) to write to the debug log
     */
    public static function endDebugLogTempStream($message = '%s'): void
    {
        if (is_resource(self::$debug_log_temp_stream_handle)) {
            rewind(self::$debug_log_temp_stream_handle);
            $stream_contents = stream_get_contents(self::$debug_log_temp_stream_handle);

            if (self::$remove_bot_token) {
                $stream_contents = preg_replace('/\/bot(\d+):[\w\-]+\//', '/botBOT_TOKEN_REMOVED/', $stream_contents);
            }

            self::debug(sprintf($message, $stream_contents));
            fclose(self::$debug_log_temp_stream_handle);
            self::$debug_log_temp_stream_handle = null;
        }
    }

    /**
     * Handle any logging method call.
     *
     * @param string $name
     * @param array  $arguments
     */
    public static function __callStatic(string $name, array $arguments)
    {
        // Get the correct logger instance.
        $logger = null;
        if (in_array($name, ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug',], true)) {
            $logger = self::$logger;
        } elseif ($name === 'update') {
            $logger = self::$update_logger;
            $name   = 'info';
        }

        // Clearly we have no logging enabled.
        if ($logger === null) {
            return;
        }

        // Replace any placeholders from the passed context.
        if (count($arguments) >= 2) {
            $arguments[0] = self::interpolate($arguments[0], $arguments[1]);
        }

        call_user_func_array([$logger, $name], $arguments);
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @see https://www.php-fig.org/psr/psr-3/#12-message
     *
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    protected static function interpolate(string $message, array $context = []): string
    {
        // Build a replacement array with braces around the context keys.
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace["{{$key}}"] = $val;
            }
        }

        // Interpolate replacement values into the message and return.
        return strtr($message, $replace);
    }
}
