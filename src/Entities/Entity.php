<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace Longman\TelegramBot\Entities;

class Entity
{
    protected $bot_name;


    public function getBotName()
    {

        return $this->bot_name;
    }



    public function toJSON()
    {
        $reflection = new \ReflectionObject($this);
        $properties = $reflection->getProperties();

        $fields = array();

        foreach ($properties as $property) {
            $name = $property->getName();
            $property->setAccessible(true);
            $value = $property->getValue($this);
            $fields[$name] = $value;
        }

        $json = json_encode($fields);

        return $json;
    }
}
