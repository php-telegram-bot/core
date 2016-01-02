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
use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Entities\Chat;
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
    static protected $mysql_credentials = [];

    /**
     * PDO object
     *
     * @var \PDO
     */
    static protected $pdo;

    /**
     * table prefix
     *
     * @var string
     */
    static protected $table_prefix;

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
        self::$table_prefix = $table_prefix;

        $dsn = 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'];
        $options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
        try {
            $pdo = new \PDO($dsn, $credentials['user'], $credentials['password'], $options);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);

            //Define table
            if (!defined('TB_MESSAGES')) {
                define('TB_MESSAGES', self::$table_prefix.'messages');
            }
            if (!defined('TB_USERS')) {
                define('TB_USERS', self::$table_prefix.'users');
            }
            if (!defined('TB_CHATS')) {
                define('TB_CHATS', self::$table_prefix.'chats');
            }
            if (!defined('TB_USERS_CHATS')) {
                define('TB_USERS_CHATS', self::$table_prefix.'users_chats');
            }

        } catch (\PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        self::$pdo = $pdo;
        return self::$pdo;
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

            if (!is_null($limit)) {
                $query .=' LIMIT :limit ';
            }

            $sth = self::$pdo->prepare($query);
            $sth->bindParam(':limit', $limit, \PDO::PARAM_INT);
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
     * Insert users eventually connection to  chats
     *
     * @return bool
     */

    public static function insertUser(User $user, $date, Chat $chat = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        $user_id = $user->getId();
        $username = $user->getUsername();
        $first_name = $user->getFirstName();
        $last_name = $user->getLastName();

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

            $sth1->bindParam(':id', $user_id, \PDO::PARAM_INT);
            $sth1->bindParam(':username', $username, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':first_name', $first_name, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':last_name', $last_name, \PDO::PARAM_STR, 255);
            $sth1->bindParam(':date', $date, \PDO::PARAM_STR);

            $status = $sth1->execute();

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
        //insert also the relationship to the chat
        if (!is_null($chat)) {
            $chat_id = $chat->getId();
            try {
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

            } catch (PDOException $e) {
                throw new TelegramException($e->getMessage());
            }
        }
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

        $from = $message->getFrom();
        $chat = $message->getChat();

        $chat_id = $chat->getId();

        $date = self::toTimestamp($message->getDate());
        $forward_from = $message->getForwardFrom();
        $forward_date = self::toTimestamp($message->getForwardDate());
        $photo = $message->getPhoto();
        $new_chat_participant = $message->getNewChatParticipant();

        $new_chat_photo = $message->getNewChatPhoto();
        $left_chat_participant = $message->getLeftChatParticipant();

        $migrate_from_chat_id = $message->getMigrateFromChatId();
        if (is_null($migrate_from_chat_id)) {
            $migrate_from_chat_id = 0;
        }

        try {
            //chats table
            $sth2 = self::$pdo->prepare('INSERT INTO `'.TB_CHATS.'`
                (`id`, `type`, `title`, `created_at` ,`updated_at`, `old_id`)
                VALUES (:id, :type, :title, :date, :date, :oldid)
                ON DUPLICATE KEY UPDATE `type`=:type, `title`=:title, `updated_at`=:date');

            $chat_title = $chat->getTitle();
            $type = $chat->getType();

            $sth2->bindParam(':id', $chat_id, \PDO::PARAM_INT);
            $sth2->bindParam(':type', $type, \PDO::PARAM_INT);
            $sth2->bindParam(':title', $chat_title, \PDO::PARAM_STR, 255);
            $sth2->bindParam(':date', $date, \PDO::PARAM_STR);
            $sth2->bindParam(':oldid', $migrate_from_chat_id, \PDO::PARAM_INT);

            $status = $sth2->execute();

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }


        //insert user and the relation with the chat
        self::insertUser($from, $date, $chat);

        //Insert the forwarded message user in users table
        if (is_object($forward_from)) {
            self::insertUser($forward_from, $forward_date);
            $forward_from = $forward_from->getId();
        } else {
            $forward_from = '';
        }

        //Insert the new chat user
        if (is_object($new_chat_participant)) {
            self::insertUser($new_chat_participant, $date, $chat);
            $new_chat_participant = $new_chat_participant->getId();
        } else {
            $new_chat_participant = '';
        }

        //Insert the left chat user
        if (is_object($left_chat_participant)) {
            self::insertUser($left_chat_participant, $date, $chat);
            $left_chat_participant = $left_chat_participant->getId();
        } else {
            $left_chat_participant = '';
        }


        try {
            //Messages Table
            $sth = self::$pdo->prepare('INSERT IGNORE INTO `'.TB_MESSAGES.'`
                (
                `update_id`, `message_id`, `user_id`, `date`, `chat_id`, `forward_from`,
                `forward_date`, `reply_to_message`, `text`, `audio`, `document`,
                `photo`, `sticker`, `video`, `voice`, `caption`, `contact`,
                `location`, `new_chat_participant`, `left_chat_participant`,
                `new_chat_title`,`new_chat_photo`, `delete_chat_photo`, `group_chat_created`,
                `supergroup_chat_created`,  `channel_chat_created`,
                `migrate_from_chat_id`,  `migrate_to_chat_id` 
                )
                VALUES (:update_id, :message_id, :user_id, :date, :chat_id, :forward_from,
                :forward_date, :reply_to_message, :text, :audio, :document,
                :photo, :sticker, :video, :voice, :caption, :contact,
                :location, :new_chat_participant, :left_chat_participant,
                :new_chat_title, :new_chat_photo, :delete_chat_photo, :group_chat_created,
                :supergroup_chat_created, :channel_chat_created,
                :migrate_from_chat_id, :migrate_to_chat_id 
                )');

            $update_id = $update->getUpdateId();
            $message_id = $message->getMessageId();
            $from_id = $from->getId();
            
            $reply_to_message = $message->getReplyToMessage();
            $reply_to_message_id = null;
            if (is_object($reply_to_message)) {
                $reply_to_message_id = $reply_to_message->getMessageId();
            }
           
            $text = $message->getText();
            $audio = $message->getAudio();
            $document = $message->getDocument();
            $sticker = $message->getSticker();
            $video = $message->getVideo();
            $voice = $message->getVoice();
            $caption = $message->getCaption();
            $contanc = $message->getContact();
            $location = $message->getLocation();
            $new_chat_title = $message->getNewChatTitle();
            $delete_chat_photo = $message->getDeleteChatPhoto();
            $group_chat_created = $message->getGroupChatCreated();
            $supergroup_chat_created = $message->getSupergroupChatCreated();
            $channel_chat_created = $message->getChannelChatCreated();
            $migrate_from_chat_id = $message->getMigrateFromChatId();

            $sth->bindParam(':update_id', $update_id, \PDO::PARAM_INT);
            $sth->bindParam(':message_id', $message_id, \PDO::PARAM_INT);
            $sth->bindParam(':user_id', $from_id, \PDO::PARAM_INT);
            $sth->bindParam(':date', $date, \PDO::PARAM_STR);
            $sth->bindParam(':chat_id', $chat_id, \PDO::PARAM_STR);
            $sth->bindParam(':forward_from', $forward_from, \PDO::PARAM_STR);
            $sth->bindParam(':forward_date', $forward_date, \PDO::PARAM_STR);
            $sth->bindParam(':reply_to_message', $reply_to_message, \PDO::PARAM_INT);
            $sth->bindParam(':text', $text, \PDO::PARAM_STR);
            $sth->bindParam(':audio', $audio, \PDO::PARAM_STR);
            $sth->bindParam(':document', $document, \PDO::PARAM_STR);

            $var = [];
            if (is_array($photo)) {
                foreach ($photo as $elm) {
                    $var[] = json_decode($elm, true);
                }

                $photo = json_encode($var);
            } else {
                $photo = '';
            }

            $sth->bindParam(':photo', $photo, \PDO::PARAM_STR);
            $sth->bindParam(':sticker', $sticker, \PDO::PARAM_STR);
            $sth->bindParam(':video', $video, \PDO::PARAM_STR);
            $sth->bindParam(':voice', $voice, \PDO::PARAM_STR);
            $sth->bindParam(':caption', $caption, \PDO::PARAM_STR);
            $sth->bindParam(':contact', $contanct, \PDO::PARAM_STR);
            $sth->bindParam(':location', $location, \PDO::PARAM_STR);
            $sth->bindParam(':new_chat_participant', $new_chat_paticipant, \PDO::PARAM_STR);
            $sth->bindParam(':left_chat_participant', $left_chat_paticipant, \PDO::PARAM_STR);
            $sth->bindParam(':new_chat_title', $new_chat_title, \PDO::PARAM_STR);

            //Array of Photosize
            $var = [];
            if (is_array($new_chat_photo)) {
                foreach ($new_chat_photo as $elm) {
                    $var[] = json_decode($elm, true);
                }

                $new_chat_photo = json_encode($var);
            } else {
                $new_chat_photo = '';
            }

            $sth->bindParam(':new_chat_photo', $new_chat_photo, \PDO::PARAM_STR);
            $sth->bindParam(':delete_chat_photo', $delete_chat_photo, \PDO::PARAM_STR);
            $sth->bindParam(':group_chat_created', $group_chat_created, \PDO::PARAM_STR);
            $sth->bindParam(':supergroup_chat_created', $migrate_from_chat_id, \PDO::PARAM_INT);
            $sth->bindParam(':channel_chat_created', $supergroup_chat_created, \PDO::PARAM_INT);
            $sth->bindParam(':migrate_from_chat_id', $channel_chat_created, \PDO::PARAM_INT);
            $sth->bindParam(':migrate_to_chat_id', $migrate_from_chat_id, \PDO::PARAM_INT);
            $status = $sth->execute();

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        return true;
    }


    /**
     * Select Group and single Chats
     *
     * @param date string yyyy-mm-dd hh:mm:ss
     *
     * @return array selected rows
     */
    public static function selectChats(
        $select_groups = true,
        $select_super_groups = true,
        $select_users = true,
        $date_from = null,
        $date_to = null
    ) {
        if (!self::isDbConnected()) {
            return false;
        }

        if (!$select_groups & !$select_users & !$select_super_groups) {
            return false;
        }
        try {
            $query = 'SELECT * ,  
                '.TB_CHATS.'.`id` AS `chat_id`,
                '.TB_CHATS.'.`updated_at` AS `chat_updated_at`,
                '.TB_USERS.'.`id` AS `user_id`
                FROM `'.TB_CHATS.'` LEFT JOIN `'.TB_USERS.'`
                ON '.TB_CHATS.'.`id`='.TB_USERS.'.`id`';

            //Building parts of query
            $chat_or_user = '';
            $where = [];
            $tokens = [];

            if (!$select_groups || !$select_users || !$select_super_groups) {
                if ($select_groups) {
                    $where[] = TB_CHATS.'.`type` = "group"';
                }
                if ($select_super_groups) {
                    $where[] = TB_CHATS.'.`type` = "supergroup"';
                }
                if ($select_users) {
                    $where[] = TB_CHATS.'.`type` = "private"';
                }
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

            $sth = self::$pdo->prepare($query);
            $sth->execute($tokens);

            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
        return $result;
    }
}
