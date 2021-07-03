<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * Callback query command
 */
class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var callable[]
     */
    protected static $callbacks = [];

    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        //$callback_query = $this->getCallbackQuery();
        //$user_id        = $callback_query->getFrom()->getId();
        //$query_id       = $callback_query->getId();
        //$query_data     = $callback_query->getData();
        //$query_data_array = $this->proper_parse_str($query_data);

        $answer         = null;
        $callback_query = $this->getCallbackQuery();

        // Call all registered callbacks.
        foreach (self::$callbacks as $callback) {
            $answer = $callback($callback_query);
            //$answer = $callback($callback_query, $query_data_array);
        }

        return ($answer instanceof ServerResponse) ? $answer : $callback_query->answer();
    }

    /**
     * Add a new callback handler for callback queries.
     *
     * @param $callback
     */
    public static function addCallbackHandler($callback): void
    {
        if (!in_array($callback, self::$callbacks))
            self::$callbacks[] = $callback;
    }
    
     /**
     * Pharses a query string and returns an array of it.
     * https://www.php.net/manual/en/function.parse-str.php
     * @param $str
     */

    private function proper_parse_str($str)
    {
        # result array
        $arr = array();

        # split on outer delimiter
        $pairs = explode('&', $str);

      # loop through each pair
      foreach ($pairs as $i) {
        # split into name and value
        list($name,$value) = explode('=', $i, 2);

        # if name already exists
        if( isset($arr[$name]) ) {
          # stick multiple values into an array
          if( is_array($arr[$name]) ) {
            $arr[$name][] = $value;
          }
          else {
            $arr[$name] = array($arr[$name], $value);
          }
        }
        # otherwise, simply stick it in a scalar
        else {
          $arr[$name] = $value;
        }
      }

      # return result array
      return $arr;
    }
}
