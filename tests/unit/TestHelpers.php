<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Tests\Unit;

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
 * @link            https://github.com/php-telegram-bot/core
 */
class TestHelpers
{
    /**
     * Data template of a user.
     *
     * @var array
     */
    protected static $user_template = [
        'id'         => 1,
        'first_name' => 'first',
        'last_name'  => 'last',
        'username'   => 'user',
    ];

    /**
     * Data template of a chat.
     *
     * @var array
     */
    protected static $chat_template = [
        'id'                             => 1,
        'first_name'                     => 'first',
        'last_name'                      => 'last',
        'username'                       => 'name',
        'type'                           => 'private',
        'all_members_are_administrators' => false,
    ];

    /**
     * Set the value of a private/protected property of an object
     *
     * @param object $object   Object that contains the property
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
     * Set the value of a private/protected static property of a class
     *
     * @param string $class    Class that contains the static property
     * @param string $property Name of the property who's value we want to set
     * @param mixed  $value    The value to set to the property
     */
    public static function setStaticProperty($class, $property, $value)
    {
        $ref_property = new \ReflectionProperty($class, $property);
        $ref_property->setAccessible(true);
        $ref_property->setValue(null, $value);
    }

    /**
     * Return a simple fake Update object
     *
     * @param array $data Pass custom data array if needed
     *
     * @return \Longman\TelegramBot\Entities\Update
     */
    public static function getFakeUpdateObject($data = null)
    {
        $data = $data ?: [
            'update_id' => mt_rand(),
            'message'   => [
                'message_id' => mt_rand(),
                'chat'       => [
                    'id' => mt_rand(),
                ],
                'date'       => time(),
            ],
        ];
        return new Update($data, 'testbot');
    }

    /**
     * Return a fake command object for the passed command text
     *
     * @param string $command_text
     *
     * @return \Longman\TelegramBot\Entities\Update
     */
    public static function getFakeUpdateCommandObject($command_text)
    {
        $data = [
            'update_id' => mt_rand(),
            'message'   => [
                'message_id' => mt_rand(),
                'from'       => self::$user_template,
                'chat'       => self::$chat_template,
                'date'       => time(),
                'text'       => $command_text,
            ],
        ];
        return self::getFakeUpdateObject($data);
    }

    /**
     * Return a fake user object.
     *
     * @param array $data Pass custom data array if needed
     *
     * @return \Longman\TelegramBot\Entities\User
     */
    public static function getFakeUserObject(array $data = [])
    {
        ($data === null) && $data = [];

        return new User($data + self::$user_template);
    }

    /**
     * Return a fake chat object.
     *
     * @param array $data Pass custom data array if needed
     *
     * @return \Longman\TelegramBot\Entities\Chat
     */
    public static function getFakeChatObject(array $data = [])
    {
        ($data === null) && $data = [];

        return new Chat($data + self::$chat_template);
    }

    /**
     * Get fake recorded audio track
     *
     * @return array
     */
    public static function getFakeRecordedAudio()
    {
        $mime_type = ['audio/ogg', 'audio/mpeg', 'audio/vnd.wave', 'audio/x-ms-wma', 'audio/basic'];
        $data      = [
            'file_id'   => mt_rand(1, 999),
            'duration'  => (string) mt_rand(1, 99) . ':' . mt_rand(1, 60),
            'performer' => 'phpunit',
            'title'     => 'track from phpunit',
            'mime_type' => $mime_type[array_rand($mime_type, 1)],
            'file_size' => mt_rand(1, 99999),
        ];

        return $data;
    }

    /**
     * Return a fake message object using the passed ids.
     *
     * @param array $message_data Pass custom message data array if needed
     * @param array $user_data    Pass custom user data array if needed
     * @param array $chat_data    Pass custom chat data array if needed
     *
     * @return \Longman\TelegramBot\Entities\Message
     */
    public static function getFakeMessageObject(array $message_data = [], array $user_data = [], array $chat_data = [])
    {
        ($message_data === null) && $message_data = [];
        ($user_data === null) && $user_data = [];
        ($chat_data === null) && $chat_data = [];

        return new Message($message_data + [
            'message_id' => mt_rand(),
            'from'       => $user_data + self::$user_template,
            'chat'       => $chat_data + self::$chat_template,
            'date'       => time(),
            'text'       => 'dummy',
        ], 'testbot');
    }

    /**
     * Start a fake conversation for the passed command and return the randomly generated ids.
     *
     * @return array
     */
    public static function startFakeConversation()
    {
        if (!DB::isDbConnected()) {
            return false;
        }

        //Just get some random values.
        $message_id = mt_rand();
        $user_id    = mt_rand();
        $chat_id    = mt_rand();

        //Make sure we have a valid user and chat available.
        $message = self::getFakeMessageObject(['message_id' => $message_id], ['id' => $user_id], ['id' => $chat_id]);
        DB::insertMessageRequest($message);
        DB::insertUser($message->getFrom(), null, $message->getChat());

        return compact('message_id', 'user_id', 'chat_id');
    }

    /**
     * Empty all tables for the passed database
     *
     * @param  array $credentials
     */
    public static function emptyDb(array $credentials)
    {
        $dsn     = 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'];
        $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];

        $pdo = new \PDO($dsn, $credentials['user'], $credentials['password'], $options);
        $pdo->prepare('
            DELETE FROM `conversation`;
            DELETE FROM `telegram_update`;
            DELETE FROM `chosen_inline_result`;
            DELETE FROM `inline_query`;
            DELETE FROM `message`;
            DELETE FROM `user_chat`;
            DELETE FROM `chat`;
            DELETE FROM `user`;
        ')->execute();
    }
}
