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
        $fields = $this->reflect($this);
        $json = json_encode($fields);

        return $json;
    }


    public function reflect($object)
    {
        $reflection = new \ReflectionObject($object);
        $properties = $reflection->getProperties();

        $fields = [];

        foreach ($properties as $property) {
            $name = $property->getName();

            if ($name == 'bot_name') {
                continue;
            }

            if (!$property->isPrivate()) {
                if (is_object($object->$name)) {
                    $fields[$name] = $this->reflect($object->$name);
                } else {
                    $property->setAccessible(true);
                    $value = $property->getValue($object);
                    if (is_null($value)) {
                        continue;
                    }
                    $fields[$name] = $value;
                }
            }
        }
        return $fields;
    }


    public function __toString()
    {
        return $this->toJSON();
    }
}
