<?php

/*
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by Marco Boretto <marco.bore@gmail.com>
*/
namespace Longman\TelegramBot;

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @package         Telegram
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */

class DB
{
    /**
     * MySQL credentials
     *
     * @var array
     */
    static protected $mysql_credentials = array();

    /**
     * PDO object
     *
     * @var \PDO
     */
    static protected $pdo;

    /**
     * Initialize
     *
     * @param array credential, string table_prefix
     */
    public static function initialize(array $credentials, $table_prefix = null)
    {
        if (empty($credentials)) {
            throw new TelegramException('MySQL credentials not provided!');
        }
        self::$mysql_credentials = $credentials;

        $dsn = 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'];
        $options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
        try {
            $pdo = new \PDO($dsn, $credentials['user'], $credentials['password'], $options);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            //Define table
            define('TB_MESSAGES', $table_prefix.'messages');
            define('TB_USERS', $table_prefix.'users');
            define('TB_CHATS', $table_prefix.'chats');
            define('TB_USERS_CHATS', $table_prefix.'users_chats');
        } catch (\PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        self::$pdo = $pdo;
    }

    /**
     * check if database Connection has been created
     *
     * @return bool
     */

    public static function isDbConnected()
    {
        if (empty(self::$pdo)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * fetch message from DB
     *
     * @param fetch Message from the DB
     *
     * @return bool/ array with data
     */

    public static function selectMessages($limit = null)
    {

        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $query = 'SELECT * FROM `'.TB_MESSAGES.'`';
            $query .= ' ORDER BY '.TB_MESSAGES.'.`update_id` DESC';

            $tokens = [];
            if (!is_null($limit)) {
                //$query .=' LIMIT :limit ';
                //$tokens[':limit'] = $limit;

                $query .=' LIMIT '.$limit;
            }
            //echo $query;
            $sth = self::$pdo->prepare($query);
            //$sth->execute($tokens);
            $sth->execute();
            $results = $sth->fetchAll(\PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
        return $results;
    }
  
    /**
     * Convert from unix timestamp to timestamp
     *
     * @return string
     */
    protected static function toTimestamp($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    /**
     * Insert request in db
     *
     * @return bool
     */
    public static function insertRequest(Update $update)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        $message = $update->getMessage();

        try {
            //Users table
            $sth1 = self::$pdo->prepare('INSERT INTO `'.TB_USERS.'`
                (
                `id`, `username`, `first_name`, `last_name`, `created_at`, `updated_at`
                )
                VALUES (
                :id, :username, :first_name, :last_name, :date, :date
                )
                ON DUPLICATE KEY UPDATE `username`=:username, `first_name`=:first_name, 
                `last_name`=:last_name, `updated_at`=:date
               ');

            $from = $message->getFrom();
            $date = self::toTimestamp($message->getDate());
            $user_id = $from->getId();
            $username = $from->getUsername();
            $first_name = $from->getFirstName();
            $last_name = $from->getLastName();

            $sth1->bindParam(':id', $user_id, \PDO::PARAM_INT);
            $sth1->bindParam(':username', $username, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':first_name', $first_name, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':last_name', $last_name, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':date', $date, \PDO::PARAM_STR);

            $status = $sth1->execute();

            //chats table
            $sth2 = self::$pdo->prepare('INSERT INTO `'.TB_CHATS.'`
                (
                `id`, `title`, `created_at` ,`updated_at`
                )
                VALUES (:id, :title, :date, :date)
                ON DUPLICATE KEY UPDATE `title`=:title, `updated_at`=:date
                ');

            $chat_id = $message->getChat()->getId();
            $chat_title = $message->getChat()->getTitle();

            $sth2->bindParam(':id', $chat_id, \PDO::PARAM_INT);
            $sth2->bindParam(':title', $chat_title, \PDO::PARAM_STR, 255);
            $sth2->bindParam(':date', $date, \PDO::PARAM_STR);

            $status = $sth2->execute();

            //user_chat table
            $sth3 = self::$pdo->prepare('INSERT IGNORE INTO `'.TB_USERS_CHATS.'`
                (
                `user_id`, `chat_id`
                )
                VALUES (:user_id, :chat_id)
                ');

            $sth3->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $sth3->bindParam(':chat_id', $chat_id, \PDO::PARAM_INT);

            $status = $sth3->execute();

            //Messages Table
            $sth = self::$pdo->prepare('INSERT IGNORE INTO `'.TB_MESSAGES.'`
                (
                `update_id`, `message_id`, `user_id`, `date`, `chat_id`, `forward_from`,
                `forward_date`, `reply_to_message`, `text`
                )
                VALUES (:update_id, :message_id, :user_id, :date, :chat_id, :forward_from,
                :forward_date, :reply_to_message, :text)');

            $update_id = $update->getUpdateId();
            $message_id = $message->getMessageId();
            $forward_from = $message->getForwardFrom();
            $forward_date = self::toTimestamp($message->getForwardDate());
            $reply_to_message = $message->getReplyToMessage();
            if (is_object($reply_to_message)) {
                $reply_to_message = $reply_to_message->toJSON();
            }
            $text = $message->getText();
    
            $sth->bindParam(':update_id', $update_id, \PDO::PARAM_INT);
            $sth->bindParam(':message_id', $message_id, \PDO::PARAM_INT);
            $sth->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $sth->bindParam(':date', $date, \PDO::PARAM_STR);
            $sth->bindParam(':chat_id', $chat_id, \PDO::PARAM_STR);
            $sth->bindParam(':forward_from', $forward_from, \PDO::PARAM_STR);
            $sth->bindParam(':forward_date', $forward_date, \PDO::PARAM_STR);
            $sth->bindParam(':reply_to_message', $reply_to_message, \PDO::PARAM_STR);
            $sth->bindParam(':text', $text, \PDO::PARAM_STR);

            $status = $sth->execute();

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        return true;
    }


    /**
     * Send Message in all the active chat
     *
     * @param date string yyyy-mm-dd hh:mm:ss
     *
     * @return bool
     */
//TODO separe send from query?
    public static function sendToActiveChats(
        $callback_function,
        array $data,
        $send_chats = true,
        $send_users = true,
        $date_from = null,
        $date_to = null
    ) {
        if (!self::isDbConnected()) {
            return false;
        }

        $callback_path = __NAMESPACE__ .'\Request';
        if (! method_exists($callback_path, $callback_function)) {
            throw new TelegramException('Methods: '.$callback_function.' not found in class Request.');
        }

        if (!$send_chats & !$send_users) {
            return false;
        }

        try {
            $query = 'SELECT * ,  
                '.TB_CHATS.'.`id` AS `chat_id`,
                '.TB_CHATS.'.`updated_at` AS `chat_updated_at`
                FROM `'.TB_CHATS.'` LEFT JOIN `'.TB_USERS.'`
                ON '.TB_CHATS.'.`id`='.TB_USERS.'.`id`';

            //Building parts of query
            $chat_or_user = '';
            $where = [];
            $tokens = [];
            if ($send_chats & !$send_users) {
                $where[] = TB_CHATS.'.`id` < 0';
            } elseif (!$send_chats & $send_users) {
                $where[] = TB_CHATS.'.`id` > 0';
            }

            if (! is_null($date_from)) {
                $where[] = TB_CHATS.'.`updated_at` >= :date_from';
                $tokens[':date_from'] =  $date_from;
            }

            if (! is_null($date_to)) {
                $where[] = TB_CHATS.'.`updated_at` <= :date_to';
                $tokens[':date_to'] = $date_to;
            }

            $a=0;
            foreach ($where as $part) {
                if ($a) {
                    $query .= ' AND '.$part;
                } else {
                    $query .= ' WHERE '.$part;
                    ++$a;
                }
            }

            $query .= ' ORDER BY '.TB_CHATS.'.`updated_at` ASC';
            //echo $query."\n";

            $sth = self::$pdo->prepare($query);
            $sth->execute($tokens);

            $results = [];
            while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
                //print_r($row);
                $data['chat_id'] = $row['chat_id'];
                $results[] = call_user_func_array($callback_path.'::'.$callback_function, array($data));
            }

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        return $results;
    }
}
