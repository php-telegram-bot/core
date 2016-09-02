<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities\InlineQuery;

use Longman\TelegramBot\Entities\Entity;

abstract class InlineEntity extends Entity
{
    /**
     * Magic method to set properties dynamically
     *
     * @param $method
     * @param $args
     *
     * @return \Longman\TelegramBot\Entities\InlineQuery\InlineEntity
     */
    public function __call($method, $args)
    {
        $action = substr($method, 0, 3);
        if ($action === 'set') {
            //Convert method to snake_case (which is the name of the property)
            $property_name        = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', substr($method, 3))), '_');
            $this->$property_name = $args[0];

            return $this;
        }

        return parent::__call($method, $args);
    }
}
