<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by Marco Boretto <marco.bore@gmail.com>
 */

namespace Longman\TelegramBot;

use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\InlineQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @package         Telegram
 * @author          Avtandil Kikabidze <akalongman@gmail.com>
 * @copyright       Avtandil Kikabidze <akalongman@gmail.com>
 * @license         http://opensource.org/licenses/mit-license.php  The MIT License (MIT)
 * @link            http://www.github.com/akalongman/php-telegram-bot
 */

/**
 * Class DB.
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
     * @var PDO
     */
    static protected $pdo;

    /**
     * Table prefix
     *
     * @var string
     */
    static protected $table_prefix;

    /**
     * Telegram class object
     *
     * @var Telegram
     */
    static protected $telegram;

    /**
     * Initialize
     *
     * @param array    $credentials  Database connection details
     * @param Telegram $telegram     Telegram object to connect with this object
     * @param string   $table_prefix Table prefix
     *
     * @return PDO PDO database object
     */
    public static function initialize(array $credentials, Telegram $telegram, $table_prefix = null)
    {
        if (empty($credentials)) {
            throw new TelegramException('MySQL credentials not provided!');
        }

        $dsn = 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'];
        $options = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
        try {
            $pdo = new \PDO($dsn, $credentials['user'], $credentials['password'], $options);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        } catch (\PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        self::$pdo = $pdo;
        self::$telegram = $telegram;
        self::$mysql_credentials = $credentials;
        self::$table_prefix = $table_prefix;

        self::defineTable();

        return self::$pdo;
    }

    /**
     * External Initialize
     *
     * Let you use the class with an external already existing Pdo Mysql connection.
     *
     * @param PDO      $external_pdo_connection PDO database object
     * @param Telegram $telegram                Telegram object to connect with this object
     * @param string   $table_prefix            Table prefix
     *
     * @return PDO PDO database object
     */
    public static function externalInitialize($external_pdo_connection, Telegram $telegram, $table_prefix = null)
    {
        if (empty($external_pdo_connection)) {
            throw new TelegramException('MySQL external connection not provided!');
        }

        self::$pdo = $pdo;
        self::$telegram = $telegram;
        self::$mysql_credentials = null;
        self::$table_prefix = $table_prefix;

        self::defineTable();

        return self::$pdo;
    }

    /**
     * Define all the table with the proper prefix
     */
    protected static function defineTable()
    {
        if (!defined('TB_TELEGRAM_UPDATE')) {
            define('TB_TELEGRAM_UPDATE', self::$table_prefix.'telegram_update');
        }
        if (!defined('TB_MESSAGE')) {
            define('TB_MESSAGE', self::$table_prefix.'message');
        }
        if (!defined('TB_INLINE_QUERY')) {
            define('TB_INLINE_QUERY', self::$table_prefix.'inline_query');
        }

        if (!defined('TB_CHOSEN_INLINE_QUERY')) {
            define('TB_CHOSEN_INLINE_QUERY', self::$table_prefix.'chosen_inline_query');
        }
        if (!defined('TB_USER')) {
            define('TB_USER', self::$table_prefix.'user');
        }
        if (!defined('TB_CHAT')) {
            define('TB_CHAT', self::$table_prefix.'chat');
        }
        if (!defined('TB_USER_CHAT')) {
            define('TB_USER_CHAT', self::$table_prefix.'user_chat');
        }
    }

    /**
     * Check if database connection has been created
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
     * Fetch update(s) from DB
     *
     * @param int $limit Limit the number of updates to fetch
     *
     * @return array|bool Fetched data or false if not connected
     */
    public static function selectTelegramUpdate($limit = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $query = 'SELECT `id` FROM `' . TB_TELEGRAM_UPDATE . '` ';
            $query .= 'ORDER BY `id` DESC';

            if (!is_null($limit)) {
                $query .=' LIMIT :limit ';
            }

            $sth_select_telegram_update = self::$pdo->prepare($query);
            $sth_select_telegram_update->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $sth_select_telegram_update->execute();
            $results = $sth_select_telegram_update->fetchAll(\PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        return $results;
    }

    /**
     * Fetch message(s) from DB
     *
     * @param int $limit Limit the number of messages to fetch
     *
     * @return array|bool Fetched data or false if not connected
     */
    public static function selectMessages($limit = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            //message table
            $query = 'SELECT * FROM `' . TB_MESSAGE . '` ';
            $query .= 'WHERE ' . TB_MESSAGE . '.`update_id` != 0 ';
            $query .= 'ORDER BY ' . TB_MESSAGE . '.`message_id` DESC';

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
     * @param int $time Unix timestamp
     *
     * @return null|string Timestamp if a time has been passed, else null
     */
    protected static function getTimestamp($time = null)
    {
        if (is_null($time)) {
            return null;
        }
        return date('Y-m-d H:i:s', $time);
    }

    /**
     * Insert entry to telegram_update table
     *
     * @todo Needs to return something if successful
     *
     * @param int $id
     * @param int $message_id
     * @param int $inline_query_id
     * @param int $chosen_inline_query_id
     *
     * @return bool|null
     */
    public static function insertTelegramUpdate($id, $message_id, $inline_query_id, $chosen_inline_query_id)
    {
        if (is_null($message_id) && is_null($inline_query_id) && is_null($chosen_inline_query_id)) {
            throw new TelegramException('Error both query_id and  message_id are null');
        }

        if (!self::isDbConnected()) {
            return false;
        }

        try {
            //telegram_update table
            $sth_insert_telegram_update = self::$pdo->prepare('INSERT IGNORE INTO `' . TB_TELEGRAM_UPDATE . '`
                (
                `id`, `message_id`, `inline_query_id`, `chosen_inline_query_id`
                )
                VALUES (
                :id, :message_id, :inline_query_id, :chosen_inline_query_id
                )
                ');

            $sth_insert_telegram_update->bindParam(':id', $id, \PDO::PARAM_INT);
            $sth_insert_telegram_update->bindParam(':message_id', $message_id, \PDO::PARAM_INT);
            $sth_insert_telegram_update->bindParam(':inline_query_id', $inline_query_id, \PDO::PARAM_INT);
            $sth_insert_telegram_update->bindParam(':chosen_inline_query_id', $chosen_inline_query_id, \PDO::PARAM_INT);

            $status = $sth_insert_telegram_update->execute();

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert users and save their connection to chats
     *
     * @todo Needs to return something if successful
     *
     * @param  Entities\User $user
     * @param  string        $date
     * @param  Entities\Chat $chat
     *
     * @return bool|null
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
            //user table
            $sth1 = self::$pdo->prepare('INSERT INTO `' . TB_USER . '`
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
                $sth3 = self::$pdo->prepare('INSERT IGNORE INTO `' . TB_USER_CHAT . '`
                    (
                    `user_id`, `chat_id`
                    )
                    VALUES (
                    :user_id, :chat_id
                    )');

                $sth3->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
                $sth3->bindParam(':chat_id', $chat_id, \PDO::PARAM_INT);

                $status = $sth3->execute();

            } catch (PDOException $e) {
                throw new TelegramException($e->getMessage());
            }
        }
    }

    /**
     * Insert request into database
     *
     * @param Entities\Update &$update
     *
     * @return bool
     */
    public static function insertRequest(Update &$update)
    {
        $update_id = $update->getUpdateId();
        if ($update->getUpdateType() == 'message') {
            $message = $update->getMessage();
            $message_id = $message->getMessageId();
            self::insertMessageRequest($message);
            return self::insertTelegramUpdate($update_id, $message_id, null, null);
        } elseif ($update->getUpdateType() == 'inline_query') {
            $inline_query = $update->getInlineQuery();
            $inline_query_id = $inline_query->getId();
            self::insertInlineQueryRequest($inline_query);
            return self::insertTelegramUpdate($update_id, null, $inline_query_id, null);
        } elseif ($update->getUpdateType() == 'chosen_inline_result') {
            $chosen_inline_query = $update->getChosenInlineResult();

            if (!self::isDbConnected()) {
                return false;
            }
            try {
                //Inline query Table
                $mysql_query = 'INSERT INTO `' . TB_CHOSEN_INLINE_QUERY . '`
                    (
                    `result_id`, `user_id`, `query`, `created_at`
                    )
                    VALUES (
                    :result_id, :user_id, :query, :created_at
                    )';

                $sth_insert_chosen_inline_query = self::$pdo->prepare($mysql_query);

                $date = self::getTimestamp(time());
                $result_id = $chosen_inline_query->getResultId();
                $from = $chosen_inline_query->getFrom();
                $user_id = null;
                if (is_object($from)) {
                    $user_id = $from->getId();
                    self::insertUser($from, $date);
                }

                $query = $chosen_inline_query->getQuery();

                $sth_insert_chosen_inline_query->bindParam(':result_id', $result_id, \PDO::PARAM_STR);
                $sth_insert_chosen_inline_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
                $sth_insert_chosen_inline_query->bindParam(':query', $query, \PDO::PARAM_STR);
                $sth_insert_chosen_inline_query->bindParam(':created_at', $date, \PDO::PARAM_STR);

                $status = $sth_insert_chosen_inline_query->execute();
                $chosen_inline_query_local_id = self::$pdo->lastInsertId();

            } catch (PDOException $e) {
                throw new TelegramException($e->getMessage());
            }

            return self::insertTelegramUpdate($update_id, null, null, $chosen_inline_query_local_id);
        }
    }

    /**
     * Insert inline query request into database
     *
     * @todo No return value at the end. Just return true?
     *
     * @param Entities\InlineQuery &$inline_query
     *
     * @return bool
     */
    public static function insertInlineQueryRequest(InlineQuery &$inline_query)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        try {
            //Inline query Table
            $mysql_query = 'INSERT IGNORE INTO `' . TB_INLINE_QUERY . '`
                (
                `id`, `user_id`, `query`, `offset`, `created_at`
                )
                VALUES (
                :inline_query_id, :user_id, :query, :param_offset, :created_at
                )';

            $sth_insert_inline_query = self::$pdo->prepare($mysql_query);

            $date = self::getTimestamp(time());
            $inline_query_id = (int) $inline_query->getId();
            $from = $inline_query->getFrom();
            $user_id = null;
            if (is_object($from)) {
                $user_id = $from->getId();
                self::insertUser($from, $date);
            }

            $query = $inline_query->getQuery();
            $offset = $inline_query->getOffset();

            $sth_insert_inline_query->bindParam(':inline_query_id', $inline_query_id, \PDO::PARAM_INT);
            $sth_insert_inline_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $sth_insert_inline_query->bindParam(':query', $query, \PDO::PARAM_STR);
            $sth_insert_inline_query->bindParam(':param_offset', $offset, \PDO::PARAM_STR);
            $sth_insert_inline_query->bindParam(':created_at', $date, \PDO::PARAM_STR);

            $status = $sth_insert_inline_query->execute();

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert Message request in db
     *
     * @param Entities\Message &$message
     *
     * @return bool If the insert was successful
     */
    public static function insertMessageRequest(Message &$message)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        $from = $message->getFrom();
        $chat = $message->getChat();

        $chat_id = $chat->getId();

        $date = self::getTimestamp($message->getDate());
        $forward_from = $message->getForwardFrom();
        $forward_date = self::getTimestamp($message->getForwardDate());

        $photo = $message->getPhoto();
        $new_chat_participant = $message->getNewChatParticipant();

        $new_chat_photo = $message->getNewChatPhoto();
        $left_chat_participant = $message->getLeftChatParticipant();

        $migrate_from_chat_id = $message->getMigrateFromChatId();

        try {
            //chat table
            $sth2 = self::$pdo->prepare('INSERT INTO `' . TB_CHAT . '`
                (
                `id`, `type`, `title`, `created_at` ,`updated_at`, `old_id`
                )
                VALUES (
                :id, :type, :title, :date, :date, :oldid
                )
                ON DUPLICATE KEY UPDATE `type`=:type, `title`=:title, `updated_at`=:date
                ');

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

        //Insert user and the relation with the chat
        self::insertUser($from, $date, $chat);

        //Insert the forwarded message user in users table
        $forward_from = null;
        if (is_object($forward_from)) {
            self::insertUser($forward_from, $forward_date);
            $forward_from = $forward_from->getId();
        }
        
        if ($new_chat_participant) {
            //Insert the new chat user
            self::insertUser($new_chat_participant, $date, $chat);
            $new_chat_participant = $new_chat_participant->getId();
        } elseif ($left_chat_participant) {
            //Insert the left chat user
            self::insertUser($left_chat_participant, $date, $chat);
            $left_chat_participant = $left_chat_participant->getId();
        }

        try {
            //message Table
            $sth = self::$pdo->prepare('INSERT IGNORE INTO `' . TB_MESSAGE . '`
                (
                `id`, `user_id`, `date`, `chat_id`, `forward_from`,
                `forward_date`, `reply_to_message`, `text`, `audio`, `document`,
                `photo`, `sticker`, `video`, `voice`, `caption`, `contact`,
                `location`, `new_chat_participant`, `left_chat_participant`,
                `new_chat_title`,`new_chat_photo`, `delete_chat_photo`, `group_chat_created`,
                `supergroup_chat_created`, `channel_chat_created`,
                `migrate_from_chat_id`, `migrate_to_chat_id`
                )
                VALUES (
                :message_id, :user_id, :date, :chat_id, :forward_from,
                :forward_date, :reply_to_message, :text, :audio, :document,
                :photo, :sticker, :video, :voice, :caption, :contact,
                :location, :new_chat_participant, :left_chat_participant,
                :new_chat_title, :new_chat_photo, :delete_chat_photo, :group_chat_created,
                :supergroup_chat_created, :channel_chat_created,
                :migrate_from_chat_id, :migrate_to_chat_id
                )');

            $message_id = $message->getMessageId();
            $from_id = $from->getId();

            $reply_to_message = $message->getReplyToMessage();
            $reply_to_message_id = null;
            if (is_object($reply_to_message)) {
                $reply_to_message_id = $reply_to_message->getMessageId();
                // please notice that, as explaied in the documentation, reply_to_message don't contain other
                // reply_to_message field so recursion deep is 1
                self::insertMessageRequest($reply_to_message);
            }

            $text = $message->getText();
            $audio = $message->getAudio();
            $document = $message->getDocument();
            $sticker = $message->getSticker();
            $video = $message->getVideo();
            $voice = $message->getVoice();
            $caption = $message->getCaption();
            $contact = $message->getContact();
            $location = $message->getLocation();
            $new_chat_title = $message->getNewChatTitle();
            $delete_chat_photo = $message->getDeleteChatPhoto();
            $group_chat_created = $message->getGroupChatCreated();
            $supergroup_chat_created = $message->getSupergroupChatCreated();
            $channel_chat_created = $message->getChannelChatCreated();
            $migrate_from_chat_id = $message->getMigrateFromChatId();

            $sth->bindParam(':message_id', $message_id, \PDO::PARAM_INT);
            $sth->bindParam(':user_id', $from_id, \PDO::PARAM_INT);
            $sth->bindParam(':date', $date, \PDO::PARAM_STR);
            $sth->bindParam(':chat_id', $chat_id, \PDO::PARAM_INT);
            $sth->bindParam(':forward_from', $forward_from, \PDO::PARAM_INT);
            $sth->bindParam(':forward_date', $forward_date, \PDO::PARAM_STR);
            $sth->bindParam(':reply_to_message', $reply_to_message_id, \PDO::PARAM_INT);
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
            $sth->bindParam(':contact', $contact, \PDO::PARAM_STR);
            $sth->bindParam(':location', $location, \PDO::PARAM_STR);
            $sth->bindParam(':new_chat_participant', $new_chat_participant, \PDO::PARAM_INT);
            $sth->bindParam(':left_chat_participant', $left_chat_participant, \PDO::PARAM_INT);
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
     * @param bool   $select_groups
     * @param bool   $select_super_groups
     * @param bool   $select_users
     * @param string $date_from
     * @param string $date_to
     *
     * @return array|bool Selected chats or false if invalid arguments
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
                ' . TB_CHAT . '.`id` AS `chat_id`,
                ' . TB_CHAT . '.`updated_at` AS `chat_updated_at`,
                ' . TB_USER . '.`id` AS `user_id`
                FROM `' . TB_CHAT . '` LEFT JOIN `' . TB_USER . '`
                ON ' . TB_CHAT . '.`id`=' . TB_USER . '.`id`';

            //Building parts of query
            $chat_or_user = '';
            $where = [];
            $tokens = [];

            if (!$select_groups || !$select_users || !$select_super_groups) {
                if ($select_groups) {
                    $where[] = TB_CHAT . '.`type` = "group"';
                }
                if ($select_super_groups) {
                    $where[] = TB_CHAT . '.`type` = "supergroup"';
                }
                if ($select_users) {
                    $where[] = TB_CHAT . '.`type` = "private"';
                }
            }

            if (! is_null($date_from)) {
                $where[] = TB_CHAT . '.`updated_at` >= :date_from';
                $tokens[':date_from'] =  $date_from;
            }

            if (! is_null($date_to)) {
                $where[] = TB_CHAT . '.`updated_at` <= :date_to';
                $tokens[':date_to'] = $date_to;
            }

            $a=0;
            foreach ($where as $part) {
                if ($a) {
                    $query .= ' AND ' . $part;
                } else {
                    $query .= ' WHERE ' . $part;
                    ++$a;
                }
            }

            $query .= ' ORDER BY ' . TB_CHAT . '.`updated_at` ASC';

            $sth = self::$pdo->prepare($query);
            $sth->execute($tokens);

            $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        return $result;
    }
}
