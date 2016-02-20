<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests;

use Longman\TelegramBot\Entities\Update;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class Helpers
{
    /**
     * Set the value of a private/protected property of an object
     *
     * @param object $object   Object that contains the private property
     * @param string $property Name of the property who's value we want to set
     * @param mixed  $value    The value to set to the property
     */
    public static function setObjectProperty($object, $property, $value)
    {
        $ref_object   = new \ReflectionObject($object);
        $ref_property = $ref_object->getProperty($property);
        $ref_property->setAccessible(true);
        $ref_property->setValue($object, $value);
    }

    /**
     * Return a simple fake Update object
     *
     * @return Entities\Update
     */
    public static function getFakeUpdateObject()
    {
        $data = [
            'update_id' => 1,
            'message'   => [
                'message_id' => 1,
                'chat' => [
                    'id'   => 1,
                ],
                'date' => 1,
            ]
        ];
        return new Update($data, 'botname');
    }
}
