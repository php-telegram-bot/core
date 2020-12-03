<?php

/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit\Entities;

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Tests\Unit\TestCase;

/**
 * @link            https://github.com/php-telegram-bot/core
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @package         TelegramTest
 */
class UpdateTest extends TestCase
{
    public function testUpdateCast(): void
    {
        $json = '{
            "update_id":137809336,
            "message":{
                "message_id":4479,
                "from":{"id":123,"first_name":"John","username":"MJohn"},
                "chat":{"id":-123,"title":"MyChat","type":"group"},
                "date":1449092987,
                "reply_to_message":{
                    "message_id":11,
                    "from":{"id":121,"first_name":"Myname","username":"mybot"},
                    "chat":{"id":-123,"title":"MyChat","type":"group"},
                    "date":1449092984,
                    "text":"type some text"
                },
                "text":"some text"
            }
        }';

        $struct = json_decode($json, true);
        $update = new Update($struct, 'mybot');

        $array_string_after = json_decode($update->toJson(), true);
        self::assertEquals($struct, $array_string_after);
    }
}
