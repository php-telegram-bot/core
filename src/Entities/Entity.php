<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Entities;

use Exception;
use Longman\TelegramBot\Entities\InlineQuery\InlineEntity;
use Longman\TelegramBot\TelegramLog;

/**
 * Class Entity
 *
 * This is the base class for all entities.
 *
 * @link https://core.telegram.org/bots/api#available-types
 *
 * @method array  getRawData()     Get the raw data passed to this entity
 * @method string getBotUsername() Return the bot name passed to this entity
 */
abstract class Entity
{
    /**
     * Entity constructor.
     *
     * @todo Get rid of the $bot_username, it shouldn't be here!
     *
     * @param array  $data
     * @param string $bot_username
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct($data, $bot_username = '')
    {
        //Make sure we're not raw_data inception-ing
        if (array_key_exists('raw_data', $data)) {
            if ($data['raw_data'] === null) {
                unset($data['raw_data']);
            }
        } else {
            $data['raw_data'] = $data;
        }

        $data['bot_username'] = $bot_username;
        $this->assignMemberVariables($data);
        $this->validate();
    }

    /**
     * Perform to json
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->getRawData());
    }

    /**
     * Perform to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Helper to set member variables
     *
     * @param array $data
     */
    protected function assignMemberVariables(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get the list of the properties that are themselves Entities
     *
     * @return array
     */
    protected function subEntities()
    {
        return [];
    }

    /**
     * Perform any special entity validation
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    protected function validate()
    {
    }

    /**
     * Get a property from the current Entity
     *
     * @param mixed $property
     * @param mixed $default
     *
     * @return mixed
     */
    public function getProperty($property, $default = null)
    {
        if (isset($this->$property)) {
            return $this->$property;
        }

        return $default;
    }

    /**
     * Return the variable for the called getter or magically set properties dynamically.
     *
     * @param $method
     * @param $args
     *
     * @return mixed|null
     */
    public function __call($method, $args)
    {
        //Convert method to snake_case (which is the name of the property)
        $property_name = strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', substr($method, 3)), '_'));

        $action = substr($method, 0, 3);
        if ($action === 'get') {
            $property = $this->getProperty($property_name);

            if ($property !== null) {
                //Get all sub-Entities of the current Entity
                $sub_entities = $this->subEntities();

                if (isset($sub_entities[$property_name])) {
                    return new $sub_entities[$property_name]($property, $this->getProperty('bot_username'));
                }

                return $property;
            }
        } elseif ($action === 'set') {
            // Limit setters to specific classes.
            if ($this instanceof InlineEntity || $this instanceof Keyboard || $this instanceof KeyboardButton) {
                $this->$property_name = $args[0];

                return $this;
            }
        }

        return null;
    }

    /**
     * Return an array of nice objects from an array of object arrays
     *
     * This method is used to generate pretty object arrays
     * mainly for PhotoSize and Entities object arrays.
     *
     * @param string $class
     * @param string $property
     *
     * @return array
     */
    protected function makePrettyObjectArray($class, $property)
    {
        $new_objects = [];

        try {
            if ($objects = $this->getProperty($property)) {
                foreach ($objects as $object) {
                    if (!empty($object)) {
                        $new_objects[] = new $class($object);
                    }
                }
            }
        } catch (Exception $e) {
            $new_objects = [];
        }

        return $new_objects;
    }

    /**
     * Escape markdown special characters
     *
     * @param string $string
     *
     * @return string
     */
    public function escapeMarkdown($string)
    {
        return str_replace(
            ['[', '`', '*', '_',],
            ['\[', '\`', '\*', '\_',],
            $string
        );
    }

    /**
     * Try to mention the user
     *
     * Mention the user with the username otherwise print first and last name
     * if the $escape_markdown argument is true special characters are escaped from the output
     *
     * @param bool $escape_markdown
     *
     * @return string|null
     */
    public function tryMention($escape_markdown = false)
    {
        //TryMention only makes sense for the User and Chat entity.
        if (!($this instanceof User || $this instanceof Chat)) {
            return null;
        }

        //Try with the username first...
        $name        = $this->getProperty('username');
        $is_username = $name !== null;

        if ($name === null) {
            //...otherwise try with the names.
            $name      = $this->getProperty('first_name');
            $last_name = $this->getProperty('last_name');
            if ($last_name !== null) {
                $name .= ' ' . $last_name;
            }
        }

        if ($escape_markdown) {
            $name = $this->escapeMarkdown($name);
        }

        return ($is_username ? '@' : '') . $name;
    }
}
