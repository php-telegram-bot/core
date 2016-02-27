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

/**
 * Class Logger.
 */
class Logger
{
    /**
     * Exception log path
     *
     * @var string
     */
    static protected $exception_log_path = null;

    /**
     * Initialize
     *
     * @param string $exception_log_path
     */
    public static function initialize($exception_log_path)
    {
        self::$exception_log_path = $exception_log_path;
    }

    /**
     * Log exception
     *
     * @param string $text
     *
     * @return bool
     */
    public static function logException($text)
    {
        if (!is_null(self::$exception_log_path)) {
            return file_put_contents(
                self::$exception_log_path,
                date('Y-m-d H:i:s', time()) . ' ' . $text . "\n",
                FILE_APPEND
            );
        }
        return 0;
    }
}
