<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Exception;

/**
 * Main exception class used for exception handling
 */
class TelegramException extends \Exception
{
    /**
     * Exception constructor that writes the exception message to the logfile
     *
     * @param string  $message Error message
     * @param integer $code    Error code
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);

        $path = 'TelegramException.log';
        $status = file_put_contents(
            $path,
            date('Y-m-d H:i:s', time()) . ' ' . self::__toString() . "\n",
            FILE_APPEND
        );
    }
}
