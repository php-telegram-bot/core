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

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\User;

/**
 * @package         TelegramTest
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */
class TestHelpers
{
    /**
     * Data template of a user.
     *
     * @var array
     */
    protected static $user_template = [
        'id' => 1,
        'first_name' => 'first',
        'last_name' => 'last',
        'username' => 'user',
    ];

    /**
     * Data template of a chat.
     *
     * @var array
     */
    protected static $chat_template = [
        'id' => 1,
        'first_name' => 'first',
        'last_name' => 'last',
        'username' => 'name',
        'type' => 'private',
    ];

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
     * @param array $data Pass custom data array if needed
     *
     * @return Entities\Update
     */
    public static function getFakeUpdateObject($data = null)
    {
        $data = $data ?: [
            'update_id' => 1,
            'message'   => [
                'message_id' => 1,
                'chat' => [
                    'id' => 1,
                ],
                'date' => 1,
            ]
        ];
        return new Update($data, 'botname');
    }

    /**
     * Return a fake command object for the passed command text
     *
     * @param string $command_text
     *
     * @return Entities\Update
     */
    public static function getFakeUpdateCommandObject($command_text)
    {
        $data = [
            'update_id' => 1,
            'message' => [
                'message_id' => 1,
                'from'       => self::$user_template,
                'chat'       => self::$chat_template,
                'date'       => 1,
                'text'       => $command_text,
            ],
        ];
        return self::getFakeUpdateObject($data);
    }

    /**
     * Return a fake user object.
     *
     * @return Entities\User
     */
    public static function getFakeUserObject()
    {
        return new User(self::$user_template);
    }

    /**
     * Return a fake chat object.
     *
     * @return Entities\Chat
     */
    public static function getFakeChatObject()
    {
        return new Chat(self::$chat_template);
    }

    /**
     * Return a fake message object using the passed ids.
     *
     * @param integer $message_id
     * @param integer $user_id
     * @param integer $chat_id
     *
     * @return Entities\Message
     */
    public static function getFakeMessageObject($message_id = 1, $user_id = 1, $chat_id = 1)
    {
        return new Message([
            'message_id' => $message_id,
            'from'       => ['id' => $user_id] + self::$user_template,
            'chat'       => ['id' => $chat_id] + self::$chat_template,
            'date'       => 1,
        ], 'botname');
    }

    /**
     * Start a fake conversation for the passed command and return the randomly generated ids.
     *
     * @param string $command
     * @return array
     */
    public static function startFakeConversation($command)
    {
        if (!DB::isDbConnected()) {
            return false;
        }

        //Just get some random values.
        $message_id = rand();
        $user_id = rand();
        $chat_id = rand();

        //Make sure we have a valid user and chat available.
        $message = self::getFakeMessageObject($message_id, $user_id, $chat_id);
        DB::insertMessageRequest($message);
        DB::insertUser($message->getFrom(), null, $message->getChat());

        return compact('message_id', 'user_id', 'chat_id');
    }

    /**
     * Empty all tables for the passed database
     *
     * @param  array $credentials
     */
    public static function emptyDB(array $credentials)
    {
        $dsn = 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'];
        $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
        try {
            $pdo = new \PDO($dsn, $credentials['user'], $credentials['password'], $options);
            $pdo->prepare('
                DELETE FROM `conversation`;
                DELETE FROM `telegram_update`;
                DELETE FROM `chosen_inline_query`;
                DELETE FROM `inline_query`;
                DELETE FROM `message`;
                DELETE FROM `user_chat`;
                DELETE FROM `chat`;
                DELETE FROM `user`;
            ')->execute();
        } catch (\Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }
}
