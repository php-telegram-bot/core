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

use Exception;
use Longman\TelegramBot\Exception\TelegramLogException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class TelegramLog
 *
 * @todo Clean out all deprecated code in the near future!
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
     * @var LoggerInterface|Logger
     */
    protected static $logger;

    /**
     * Logger instance for update
     *
     * @var LoggerInterface|Logger
     */
    protected static $update_logger;

    /**
     * Path for error log
     *
     * @var string
     * @deprecated
     */
    protected static $error_log_path;

    /**
     * Path for debug log
     *
     * @var string
     * @deprecated
     */
    protected static $debug_log_path;

    /**
     * Path for update log
     *
     * @var string
     * @deprecated
     */
    protected static $update_log_path;

    /**
     * Temporary stream handle for debug log
     *
     * @var resource|null
     */
    protected static $debug_log_temp_stream_handle;

    /**
     * Initialise Logger instance, optionally passing an existing one.
     *
     * @param LoggerInterface|null $logger
     * @param LoggerInterface|null $update_logger
     */
    public static function initialize(LoggerInterface $logger = null, LoggerInterface $update_logger = null)
    {
        // Clearly deprecated code still being executed.
        if ($logger === null) {
            (defined('PHPUNIT_TESTSUITE') && PHPUNIT_TESTSUITE) || trigger_error('A PSR-3 compatible LoggerInterface object must be provided. Initialise with a preconfigured logger instance.', E_USER_DEPRECATED);
            $logger = new Logger('bot_log');
        } elseif ($logger instanceof Logger) {
            foreach ($logger->getHandlers() as $handler) {
                if (method_exists($handler, 'getLevel') && $handler->getLevel() === Logger::ERROR) {
                    self::$error_log_path = 'true';
                }
                if (method_exists($handler, 'getLevel') && $handler->getLevel() === Logger::DEBUG) {
                    self::$debug_log_path = 'true';
                }
            }
        }

        // Fallback to NullLogger.
        self::$logger        = $logger ?: new NullLogger();
        self::$update_logger = $update_logger ?: new NullLogger();
    }

    /**
     * Initialise error log (deprecated)
     *
     * @param string $path
     *
     * @return LoggerInterface
     * @throws Exception
     *
     * @deprecated Initialise a preconfigured logger instance instead.
     */
    public static function initErrorLog($path)
    {
        (defined('PHPUNIT_TESTSUITE') && PHPUNIT_TESTSUITE) || trigger_error(__METHOD__ . ' is deprecated and will be removed soon. Initialise with a preconfigured logger instance instead using "TelegramLog::initialize($logger)".', E_USER_DEPRECATED);

        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for error log');
        }
        self::initialize();

        // Deprecated code used as fallback.
        if (self::$logger instanceof Logger) {
            self::$error_log_path = $path;

            self::$logger->pushHandler(
                (new StreamHandler(self::$error_log_path, Logger::ERROR))
                    ->setFormatter(new LineFormatter(null, null, true))
            );
        }

        return self::$logger;
    }

    /**
     * Initialise debug log (deprecated)
     *
     * @param string $path
     *
     * @return LoggerInterface
     * @throws Exception
     *
     * @deprecated Initialise a preconfigured logger instance instead.
     */
    public static function initDebugLog($path)
    {
        (defined('PHPUNIT_TESTSUITE') && PHPUNIT_TESTSUITE) || trigger_error(__METHOD__ . ' is deprecated and will be removed soon. Initialise with a preconfigured logger instance instead using "TelegramLog::initialize($logger)".', E_USER_DEPRECATED);

        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for debug log');
        }
        self::initialize();

        // Deprecated code used as fallback.
        if (self::$logger instanceof Logger) {
            self::$debug_log_path = $path;

            self::$logger->pushHandler(
                (new StreamHandler(self::$debug_log_path, Logger::DEBUG))
                    ->setFormatter(new LineFormatter(null, null, true))
            );
        }

        return self::$logger;
    }

    /**
     * Initialise update log (deprecated)
     *
     * @param string $path
     *
     * @return LoggerInterface
     * @throws Exception
     *
     * @deprecated Initialise a preconfigured logger instance instead.
     */
    public static function initUpdateLog($path)
    {
        (defined('PHPUNIT_TESTSUITE') && PHPUNIT_TESTSUITE) || trigger_error(__METHOD__ . ' is deprecated and will be removed soon. Initialise with a preconfigured logger instance instead using "TelegramLog::initialize($logger)".', E_USER_DEPRECATED);

        if ($path === null || $path === '') {
            throw new TelegramLogException('Empty path for update log');
        }
        self::$update_log_path = $path;

        if (self::$update_logger === null || self::$update_logger instanceof NullLogger) {
            self::$update_logger = new Logger('bot_update_log');

            self::$update_logger->pushHandler(
                (new StreamHandler(self::$update_log_path, Logger::INFO))
                    ->setFormatter(new LineFormatter('%message%' . PHP_EOL))
            );
        }

        return self::$update_logger;
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
    public static function endDebugLogTempStream($message = '%s')
    {
        if (is_resource(self::$debug_log_temp_stream_handle)) {
            rewind(self::$debug_log_temp_stream_handle);
            self::debug(sprintf($message, stream_get_contents(self::$debug_log_temp_stream_handle)));
            fclose(self::$debug_log_temp_stream_handle);
            self::$debug_log_temp_stream_handle = null;
        }
    }

    /**
     * Is error log active
     *
     * @return bool
     *
     * @deprecated Initialise a preconfigured logger instance instead.
     */
    public static function isErrorLogActive()
    {
        (defined('PHPUNIT_TESTSUITE') && PHPUNIT_TESTSUITE) || trigger_error(__METHOD__ . ' is deprecated and will be removed soon. Initialise with a preconfigured logger instance instead using "TelegramLog::initialize($logger)".', E_USER_DEPRECATED);
        return self::$error_log_path !== null;
    }

    /**
     * Is debug log active
     *
     * @return bool
     *
     * @deprecated Initialise a preconfigured logger instance instead.
     */
    public static function isDebugLogActive()
    {
        (defined('PHPUNIT_TESTSUITE') && PHPUNIT_TESTSUITE) || trigger_error(__METHOD__ . ' is deprecated and will be removed soon. Initialise with a preconfigured logger instance instead using "TelegramLog::initialize($logger)".', E_USER_DEPRECATED);
        return self::$debug_log_path !== null;
    }

    /**
     * Is update log active
     *
     * @return bool
     *
     * @deprecated Initialise a preconfigured logger instance instead.
     */
    public static function isUpdateLogActive()
    {
        (defined('PHPUNIT_TESTSUITE') && PHPUNIT_TESTSUITE) || trigger_error(__METHOD__ . ' is deprecated and will be removed soon. Initialise with a preconfigured logger instance instead using "TelegramLog::initialize($logger)".', E_USER_DEPRECATED);
        return self::$update_log_path !== null;
    }

    /**
     * Handle any logging method call.
     *
     * @param string $name
     * @param array  $arguments
     */
    public static function __callStatic($name, array $arguments)
    {
        // Get the correct logger instance.
        $logger = null;
        if (in_array($name, ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug',], true)) {
            $logger = self::$logger;
        } elseif ($name === 'update') {
            $logger = self::$update_logger;
            $name   = 'info';
        } else {
            return;
        }

        self::initialize(self::$logger, self::$update_logger);

        // Replace any placeholders from the passed context.
        if (count($arguments) >= 2) {
            if (is_array($arguments[1])) {
                $arguments[0] = self::interpolate($arguments[0], $arguments[1]);
            } else {
                // @todo Old parameter passing active, should be removed in the near future.
                $arguments[0] = vsprintf($arguments[0], array_splice($arguments, 1));
            }
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
    protected static function interpolate($message, array $context = [])
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}
