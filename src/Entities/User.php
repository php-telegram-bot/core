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

/**
 * Class User
 *
 * @link https://core.telegram.org/bots/api#user
 *
 * @property int    $id         Unique identifier for this user or bot
 * @property string $first_name User's or bot’s first name
 * @property string $last_name  Optional. User's or bot’s last name
 * @property string $username   Optional. User's or bot’s username
 *
 * @method int    getId()        Unique identifier for this user or bot
 * @method string getFirstName() User's or bot’s first name
 * @method string getLastName()  Optional. User's or bot’s last name
 * @method string getUsername()  Optional. User's or bot’s username
 */
class User extends Entity
{
    /**
     * tryMention
     *
     * @param bool $markdown
     *
     * @return string
     */
    public function tryMention($markdown = false)
    {
        if (isset($this->username)) {
            if ($markdown) {
                //Escaping md special characters
                //Please notice that just the _ is allowed in the username ` * [ are not allowed
                return $this->prependAt($this->stripMarkDown($this->username));
            }
            return $this->prependAt($this->username);
        }

        $name = $this->first_name;
        if (isset($this->last_name)) {
            $name .= ' ' . $this->last_name;
        }
        
        if ($markdown) {
            //Escaping md special characters
            return $this->stripMarkDown($name);
        }
        return $name;
    }

    /**
     * stripMarkDown
     *
     * @param string $string
     *
     * @return string
     */
    public function stripMarkDown($string)
    {
        $string = str_replace('[', '\[', $string);
        $string = str_replace('`', '\`', $string);
        $string = str_replace('*', '\*', $string);
        return str_replace('_', '\_', $string);
    }

    /**
     * prepend@
     *
     * @param string $string
     *
     * @return string
     */
    public function prependAt($string)
    {
        return '@' . $string;
    }
}
