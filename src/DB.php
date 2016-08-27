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

use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\ChosenInlineResult;
use Longman\TelegramBot\Entities\InlineQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\ReplyToMessage;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Exception\TelegramException;
use PDO;
use PDOException;

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
     * @var \Longman\TelegramBot\Telegram
     */
    static protected $telegram;

    /**
     * Initialize
     *
     * @param array                         $credentials  Database connection details
     * @param \Longman\TelegramBot\Telegram $telegram     Telegram object to connect with this object
     * @param string                        $table_prefix Table prefix
     * @param string                        $encoding     Database character encoding
     *
     * @return PDO PDO database object
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function initialize(
        array $credentials,
        Telegram $telegram,
        $table_prefix = null,
        $encoding = 'utf8mb4'
    ) {
        if (empty($credentials)) {
            throw new TelegramException('MySQL credentials not provided!');
        }

        $dsn     = 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['database'];
        $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $encoding];
        try {
            $pdo = new PDO($dsn, $credentials['user'], $credentials['password'], $options);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        self::$pdo               = $pdo;
        self::$telegram          = $telegram;
        self::$mysql_credentials = $credentials;
        self::$table_prefix      = $table_prefix;

        self::defineTables();

        return self::$pdo;
    }

    /**
     * External Initialize
     *
     * Let you use the class with an external already existing Pdo Mysql connection.
     *
     * @param PDO                           $external_pdo_connection PDO database object
     * @param \Longman\TelegramBot\Telegram $telegram                Telegram object to connect with this object
     * @param string                        $table_prefix            Table prefix
     *
     * @return PDO PDO database object
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function externalInitialize($external_pdo_connection, Telegram $telegram, $table_prefix = null)
    {
        if (empty($external_pdo_connection)) {
            throw new TelegramException('MySQL external connection not provided!');
        }

        self::$pdo               = $external_pdo_connection;
        self::$telegram          = $telegram;
        self::$mysql_credentials = null;
        self::$table_prefix      = $table_prefix;

        self::defineTables();

        return self::$pdo;
    }

    /**
     * Define all the table with the proper prefix
     */
    protected static function defineTables()
    {
        $tables = [
            'callback_query',
            'chat',
            'chosen_inline_result',
            'edited_message',
            'inline_query',
            'message',
            'telegram_update',
            'user',
            'user_chat',
        ];
        foreach ($tables as $table) {
            $table_name = 'TB_' . strtoupper($table);
            if (!defined($table_name)) {
                define($table_name, self::$table_prefix . $table);
            }
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
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function selectTelegramUpdate($limit = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sql = '
                SELECT `id`
                FROM `' . TB_TELEGRAM_UPDATE . '`
                ORDER BY `id` DESC
            ';

            if ($limit !== null) {
                $sql .= 'LIMIT :limit';
            }

            $sth = self::$pdo->prepare($sql);
            $sth->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $sth->execute();

            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Fetch message(s) from DB
     *
     * @param int $limit Limit the number of messages to fetch
     *
     * @return array|bool Fetched data or false if not connected
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function selectMessages($limit = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sql = '
                SELECT *
                FROM `' . TB_MESSAGE . '`
                WHERE `update_id` != 0
                ORDER BY `message_id` DESC
            ';

            if ($limit !== null) {
                $sql .= 'LIMIT :limit';
            }

            $sth = self::$pdo->prepare($sql);
            $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
            $sth->execute();

            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Convert from unix timestamp to timestamp
     *
     * @param int $time Unix timestamp (if null, current timestamp is used)
     *
     * @return string
     */
    protected static function getTimestamp($time = null)
    {
        if ($time === null) {
            $time = time();
        }

        return date('Y-m-d H:i:s', $time);
    }

    /**
     * Insert entry to telegram_update table
     *
     * @param int $id
     * @param int $chat_id
     * @param int $message_id
     * @param int $inline_query_id
     * @param int $chosen_inline_result_id
     * @param int $callback_query_id
     * @param int $edited_message_id
     *
     * @return bool If the insert was successful
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertTelegramUpdate(
        $id,
        $chat_id,
        $message_id,
        $inline_query_id,
        $chosen_inline_result_id,
        $callback_query_id,
        $edited_message_id
    ) {
        if ($message_id === null && $inline_query_id === null && $chosen_inline_result_id === null && $callback_query_id === null && $edited_message_id === null) {
            throw new TelegramException('message_id, inline_query_id, chosen_inline_result_id, callback_query_id, edited_message_id are all null');
        }

        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('
                INSERT IGNORE INTO `' . TB_TELEGRAM_UPDATE . '`
                (`id`, `chat_id`, `message_id`, `inline_query_id`, `chosen_inline_result_id`, `callback_query_id`, `edited_message_id`)
                VALUES
                (:id, :chat_id, :message_id, :inline_query_id, :chosen_inline_result_id, :callback_query_id, :edited_message_id)
            ');

            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
            $sth->bindParam(':message_id', $message_id, PDO::PARAM_INT);
            $sth->bindParam(':inline_query_id', $inline_query_id, PDO::PARAM_INT);
            $sth->bindParam(':chosen_inline_result_id', $chosen_inline_result_id, PDO::PARAM_INT);
            $sth->bindParam(':callback_query_id', $callback_query_id, PDO::PARAM_INT);
            $sth->bindParam(':edited_message_id', $edited_message_id, PDO::PARAM_INT);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert users and save their connection to chats
     *
     * @param  \Longman\TelegramBot\Entities\User $user
     * @param  string                             $date
     * @param  \Longman\TelegramBot\Entities\Chat $chat
     *
     * @return bool If the insert was successful
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertUser(User $user, $date, Chat $chat = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        $user_id    = $user->getId();
        $username   = $user->getUsername();
        $first_name = $user->getFirstName();
        $last_name  = $user->getLastName();

        try {
            $sth = self::$pdo->prepare('
                INSERT INTO `' . TB_USER . '`
                (`id`, `username`, `first_name`, `last_name`, `created_at`, `updated_at`)
                VALUES
                (:id, :username, :first_name, :last_name, :date, :date)
                ON DUPLICATE KEY UPDATE
                    `username`   = :username,
                    `first_name` = :first_name,
                    `last_name`  = :last_name,
                    `updated_at` = :date
            ');

            $sth->bindParam(':id', $user_id, PDO::PARAM_INT);
            $sth->bindParam(':username', $username, PDO::PARAM_STR, 255);
            $sth->bindParam(':first_name', $first_name, PDO::PARAM_STR, 255);
            $sth->bindParam(':last_name', $last_name, PDO::PARAM_STR, 255);
            $sth->bindParam(':date', $date, PDO::PARAM_STR);

            $status = $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        //insert also the relationship to the chat into user_chat table
        if ($chat instanceof Chat) {
            $chat_id = $chat->getId();
            try {
                $sth = self::$pdo->prepare('
                    INSERT IGNORE INTO `' . TB_USER_CHAT . '`
                    (`user_id`, `chat_id`)
                    VALUES
                    (:user_id, :chat_id)
                ');

                $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $sth->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);

                $status = $sth->execute();
            } catch (PDOException $e) {
                throw new TelegramException($e->getMessage());
            }
        }

        return $status;
    }

    /**
     * Insert chat
     *
     * @param  \Longman\TelegramBot\Entities\Chat $chat
     * @param  string                             $date
     * @param  int                                $migrate_to_chat_id
     *
     * @return bool If the insert was successful
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertChat(Chat $chat, $date, $migrate_to_chat_id = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        $chat_id    = $chat->getId();
        $chat_title = $chat->getTitle();
        $chat_type  = $chat->getType();

        try {
            $sth = self::$pdo->prepare('
                INSERT INTO `' . TB_CHAT . '`
                (`id`, `type`, `title`, `created_at` ,`updated_at`, `old_id`)
                VALUES
                (:id, :type, :title, :date, :date, :oldid)
                ON DUPLICATE KEY UPDATE
                    `type`       = :type,
                    `title`      = :title,
                    `updated_at` = :date
            ');

            if ($migrate_to_chat_id) {
                $chat_type = 'supergroup';

                $sth->bindParam(':id', $migrate_to_chat_id, PDO::PARAM_INT);
                $sth->bindParam(':oldid', $chat_id, PDO::PARAM_INT);
            } else {
                $sth->bindParam(':id', $chat_id, PDO::PARAM_INT);
                $sth->bindParam(':oldid', $migrate_to_chat_id, PDO::PARAM_INT);
            }

            $sth->bindParam(':type', $chat_type, PDO::PARAM_INT);
            $sth->bindParam(':title', $chat_title, PDO::PARAM_STR, 255);
            $sth->bindParam(':date', $date, PDO::PARAM_STR);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert request into database
     *
     * @todo self::$pdo->lastInsertId() - unsafe usage if expected previous insert fails?
     *
     * @param \Longman\TelegramBot\Entities\Update $update
     *
     * @return bool
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertRequest(Update $update)
    {
        $update_id   = $update->getUpdateId();
        $update_type = $update->getUpdateType();

        if ($update_type === 'message') {
            $message = $update->getMessage();

            if (self::insertMessageRequest($message)) {
                $message_id = $message->getMessageId();
                $chat_id    = $message->getChat()->getId();

                return self::insertTelegramUpdate($update_id, $chat_id, $message_id, null, null, null, null);
            }
        } elseif ($update_type === 'inline_query') {
            $inline_query = $update->getInlineQuery();

            if (self::insertInlineQueryRequest($inline_query)) {
                $inline_query_id = $inline_query->getId();

                return self::insertTelegramUpdate($update_id, null, null, $inline_query_id, null, null, null);
            }
        } elseif ($update_type === 'chosen_inline_result') {
            $chosen_inline_result = $update->getChosenInlineResult();

            if (self::insertChosenInlineResultRequest($chosen_inline_result)) {
                $chosen_inline_result_local_id = self::$pdo->lastInsertId();

                return self::insertTelegramUpdate(
                    $update_id,
                    null,
                    null,
                    null,
                    $chosen_inline_result_local_id,
                    null,
                    null
                );
            }
        } elseif ($update_type === 'callback_query') {
            $callback_query = $update->getCallbackQuery();

            if (self::insertCallbackQueryRequest($callback_query)) {
                $callback_query_id = $callback_query->getId();

                return self::insertTelegramUpdate($update_id, null, null, null, null, $callback_query_id, null);
            }
        } elseif ($update_type === 'edited_message') {
            $edited_message = $update->getEditedMessage();

            if (self::insertEditedMessageRequest($edited_message)) {
                $chat_id                 = $edited_message->getChat()->getId();
                $edited_message_local_id = self::$pdo->lastInsertId();

                return self::insertTelegramUpdate(
                    $update_id,
                    $chat_id,
                    null,
                    null,
                    null,
                    null,
                    $edited_message_local_id
                );
            }
        }

        return false;
    }

    /**
     * Insert inline query request into database
     *
     * @param \Longman\TelegramBot\Entities\InlineQuery $inline_query
     *
     * @return bool If the insert was successful
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertInlineQueryRequest(InlineQuery $inline_query)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('
                INSERT IGNORE INTO `' . TB_INLINE_QUERY . '`
                (`id`, `user_id`, `location`, `query`, `offset`, `created_at`)
                VALUES
                (:inline_query_id, :user_id, :location, :query, :param_offset, :created_at)
            ');

            $date            = self::getTimestamp();
            $inline_query_id = $inline_query->getId();
            $from            = $inline_query->getFrom();
            $user_id         = null;
            if ($from instanceof User) {
                $user_id = $from->getId();
                self::insertUser($from, $date);
            }

            $location = $inline_query->getLocation();
            $query    = $inline_query->getQuery();
            $offset   = $inline_query->getOffset();

            $sth->bindParam(':inline_query_id', $inline_query_id, PDO::PARAM_INT);
            $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $sth->bindParam(':location', $location, PDO::PARAM_STR);
            $sth->bindParam(':query', $query, PDO::PARAM_STR);
            $sth->bindParam(':param_offset', $offset, PDO::PARAM_STR);
            $sth->bindParam(':created_at', $date, PDO::PARAM_STR);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert chosen inline result request into database
     *
     * @param \Longman\TelegramBot\Entities\ChosenInlineResult $chosen_inline_result
     *
     * @return bool If the insert was successful
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertChosenInlineResultRequest(ChosenInlineResult $chosen_inline_result)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('
                INSERT INTO `' . TB_CHOSEN_INLINE_RESULT . '`
                (`result_id`, `user_id`, `location`, `inline_message_id`, `query`, `created_at`)
                VALUES
                (:result_id, :user_id, :location, :inline_message_id, :query, :created_at)
            ');

            $date      = self::getTimestamp();
            $result_id = $chosen_inline_result->getResultId();
            $from      = $chosen_inline_result->getFrom();
            $user_id   = null;
            if ($from instanceof User) {
                $user_id = $from->getId();
                self::insertUser($from, $date);
            }

            $location          = $chosen_inline_result->getLocation();
            $inline_message_id = $chosen_inline_result->getInlineMessageId();
            $query             = $chosen_inline_result->getQuery();

            $sth->bindParam(':result_id', $result_id, PDO::PARAM_STR);
            $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $sth->bindParam(':location', $location, PDO::PARAM_INT);
            $sth->bindParam(':inline_message_id', $inline_message_id, PDO::PARAM_STR);
            $sth->bindParam(':query', $query, PDO::PARAM_STR);
            $sth->bindParam(':created_at', $date, PDO::PARAM_STR);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert callback query request into database
     *
     * @param \Longman\TelegramBot\Entities\CallbackQuery $callback_query
     *
     * @return bool If the insert was successful
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertCallbackQueryRequest(CallbackQuery $callback_query)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare(
                'INSERT IGNORE INTO `' . TB_CALLBACK_QUERY . '`
                (`id`, `user_id`, `chat_id`, `message_id`, `inline_message_id`, `data`, `created_at`)
                VALUES
                (:callback_query_id, :user_id, :chat_id, :message_id, :inline_message_id, :data, :created_at)
            ');

            $date              = self::getTimestamp();
            $callback_query_id = $callback_query->getId();
            $from              = $callback_query->getFrom();
            $user_id           = null;
            if ($from instanceof User) {
                $user_id = $from->getId();
                self::insertUser($from, $date);
            }

            $message    = $callback_query->getMessage();
            $chat_id    = null;
            $message_id = null;
            if ($message instanceof Message) {
                $chat_id    = $message->getChat()->getId();
                $message_id = $message->getMessageId();

                $is_message = self::$pdo->query('
                    SELECT *
                    FROM `' . TB_MESSAGE . '`
                    WHERE `id` = ' . $message_id . '
                      AND `chat_id` = ' . $chat_id . '
                    LIMIT 1
                ')->rowCount();

                if ($is_message) {
                    self::insertEditedMessageRequest($message);
                } else {
                    self::insertMessageRequest($message);
                }
            }

            $inline_message_id = $callback_query->getInlineMessageId();
            $data              = $callback_query->getData();

            $sth->bindParam(':callback_query_id', $callback_query_id, PDO::PARAM_INT);
            $sth->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $sth->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
            $sth->bindParam(':message_id', $message_id, PDO::PARAM_INT);
            $sth->bindParam(':inline_message_id', $inline_message_id, PDO::PARAM_STR);
            $sth->bindParam(':data', $data, PDO::PARAM_STR);
            $sth->bindParam(':created_at', $date, PDO::PARAM_STR);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert Message request in db
     *
     * @param \Longman\TelegramBot\Entities\Message $message
     *
     * @return bool If the insert was successful
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertMessageRequest(Message $message)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        $from = $message->getFrom();
        $chat = $message->getChat();

        $chat_id = $chat->getId();

        $date = self::getTimestamp($message->getDate());

        $forward_from       = $message->getForwardFrom();
        $forward_from_chat  = $message->getForwardFromChat();
        $photo              = $message->getPhoto();
        $entities           = $message->getEntities();
        $new_chat_member    = $message->getNewChatMember();
        $new_chat_photo     = $message->getNewChatPhoto();
        $left_chat_member   = $message->getLeftChatMember();
        $migrate_to_chat_id = $message->getMigrateToChatId();

        //Insert chat, update chat id in case it migrated
        self::insertChat($chat, $date, $migrate_to_chat_id);

        //Insert user and the relation with the chat
        self::insertUser($from, $date, $chat);

        //Insert the forwarded message user in users table
        if ($forward_from instanceof User) {
            $forward_date = self::getTimestamp($message->getForwardDate());
            self::insertUser($forward_from, $forward_date);
            $forward_from = $forward_from->getId();
        }

        if ($forward_from_chat instanceof Chat) {
            $forward_date = self::getTimestamp($message->getForwardDate());
            self::insertChat($forward_from_chat, $forward_date);
            $forward_from_chat = $forward_from_chat->getId();
        }

        //New and left chat member
        if ($new_chat_member) {
            //Insert the new chat user
            self::insertUser($new_chat_member, $date, $chat);
            $new_chat_member = $new_chat_member->getId();
        } elseif ($left_chat_member) {
            //Insert the left chat user
            self::insertUser($left_chat_member, $date, $chat);
            $left_chat_member = $left_chat_member->getId();
        }

        try {
            $sth = self::$pdo->prepare('
                INSERT IGNORE INTO `' . TB_MESSAGE . '`
                (
                    `id`, `user_id`, `chat_id`, `date`, `forward_from`, `forward_from_chat`,
                    `forward_date`, `reply_to_chat`, `reply_to_message`, `text`, `entities`, `audio`, `document`,
                    `photo`, `sticker`, `video`, `voice`, `caption`, `contact`,
                    `location`, `venue`, `new_chat_member`, `left_chat_member`,
                    `new_chat_title`,`new_chat_photo`, `delete_chat_photo`, `group_chat_created`,
                    `supergroup_chat_created`, `channel_chat_created`,
                    `migrate_from_chat_id`, `migrate_to_chat_id`, `pinned_message`
                ) VALUES (
                    :message_id, :user_id, :chat_id, :date, :forward_from, :forward_from_chat,
                    :forward_date, :reply_to_chat, :reply_to_message, :text, :entities, :audio, :document,
                    :photo, :sticker, :video, :voice, :caption, :contact,
                    :location, :venue, :new_chat_member, :left_chat_member,
                    :new_chat_title, :new_chat_photo, :delete_chat_photo, :group_chat_created,
                    :supergroup_chat_created, :channel_chat_created,
                    :migrate_from_chat_id, :migrate_to_chat_id, :pinned_message
                )
            ');

            $message_id = $message->getMessageId();
            $from_id    = $from->getId();

            $reply_to_message    = $message->getReplyToMessage();
            $reply_to_message_id = null;
            if ($reply_to_message instanceof ReplyToMessage) {
                $reply_to_message_id = $reply_to_message->getMessageId();
                // please notice that, as explained in the documentation, reply_to_message don't contain other
                // reply_to_message field so recursion deep is 1
                self::insertMessageRequest($reply_to_message);
            }

            $text                    = $message->getText();
            $audio                   = $message->getAudio();
            $document                = $message->getDocument();
            $sticker                 = $message->getSticker();
            $video                   = $message->getVideo();
            $voice                   = $message->getVoice();
            $caption                 = $message->getCaption();
            $contact                 = $message->getContact();
            $location                = $message->getLocation();
            $venue                   = $message->getVenue();
            $new_chat_title          = $message->getNewChatTitle();
            $delete_chat_photo       = $message->getDeleteChatPhoto();
            $group_chat_created      = $message->getGroupChatCreated();
            $supergroup_chat_created = $message->getSupergroupChatCreated();
            $channel_chat_created    = $message->getChannelChatCreated();
            $migrate_from_chat_id    = $message->getMigrateFromChatId();
            $migrate_to_chat_id      = $message->getMigrateToChatId();
            $pinned_message          = $message->getPinnedMessage();

            $sth->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
            $sth->bindParam(':message_id', $message_id, PDO::PARAM_INT);
            $sth->bindParam(':user_id', $from_id, PDO::PARAM_INT);
            $sth->bindParam(':date', $date, PDO::PARAM_STR);
            $sth->bindParam(':forward_from', $forward_from, PDO::PARAM_INT);
            $sth->bindParam(':forward_from_chat', $forward_from_chat, PDO::PARAM_INT);
            $sth->bindParam(':forward_date', $forward_date, PDO::PARAM_STR);

            $reply_chat_id = null;
            if ($reply_to_message_id) {
                $reply_chat_id = $chat_id;
            }

            $var = [];
            if (is_array($entities)) {
                foreach ($entities as $elm) {
                    $var[] = json_decode($elm, true);
                }

                $entities = json_encode($var);
            } else {
                $entities = null;
            }

            $sth->bindParam(':reply_to_chat', $reply_chat_id, PDO::PARAM_INT);
            $sth->bindParam(':reply_to_message', $reply_to_message_id, PDO::PARAM_INT);
            $sth->bindParam(':text', $text, PDO::PARAM_STR);
            $sth->bindParam(':entities', $entities, PDO::PARAM_STR);
            $sth->bindParam(':audio', $audio, PDO::PARAM_STR);
            $sth->bindParam(':document', $document, PDO::PARAM_STR);

            $var = [];
            if (is_array($photo)) {
                foreach ($photo as $elm) {
                    $var[] = json_decode($elm, true);
                }

                $photo = json_encode($var);
            } else {
                $photo = '';
            }

            $sth->bindParam(':photo', $photo, PDO::PARAM_STR);
            $sth->bindParam(':sticker', $sticker, PDO::PARAM_STR);
            $sth->bindParam(':video', $video, PDO::PARAM_STR);
            $sth->bindParam(':voice', $voice, PDO::PARAM_STR);
            $sth->bindParam(':caption', $caption, PDO::PARAM_STR);
            $sth->bindParam(':contact', $contact, PDO::PARAM_STR);
            $sth->bindParam(':location', $location, PDO::PARAM_STR);
            $sth->bindParam(':venue', $venue, PDO::PARAM_STR);
            $sth->bindParam(':new_chat_member', $new_chat_member, PDO::PARAM_INT);
            $sth->bindParam(':left_chat_member', $left_chat_member, PDO::PARAM_INT);
            $sth->bindParam(':new_chat_title', $new_chat_title, PDO::PARAM_STR);

            //Array of PhotoSize
            $var = [];
            if (is_array($new_chat_photo)) {
                foreach ($new_chat_photo as $elm) {
                    $var[] = json_decode($elm, true);
                }

                $new_chat_photo = json_encode($var);
            } else {
                $new_chat_photo = '';
            }

            $sth->bindParam(':new_chat_photo', $new_chat_photo, PDO::PARAM_STR);
            $sth->bindParam(':delete_chat_photo', $delete_chat_photo, PDO::PARAM_STR);
            $sth->bindParam(':group_chat_created', $group_chat_created, PDO::PARAM_STR);
            $sth->bindParam(':supergroup_chat_created', $supergroup_chat_created, PDO::PARAM_INT);
            $sth->bindParam(':channel_chat_created', $channel_chat_created, PDO::PARAM_INT);
            $sth->bindParam(':migrate_from_chat_id', $migrate_from_chat_id, PDO::PARAM_INT);
            $sth->bindParam(':migrate_to_chat_id', $migrate_to_chat_id, PDO::PARAM_INT);
            $sth->bindParam(':pinned_message', $pinned_message, PDO::PARAM_INT);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert Edited Message request in db
     *
     * @param \Longman\TelegramBot\Entities\Message $edited_message
     *
     * @return bool If the insert was successful
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function insertEditedMessageRequest(Message $edited_message)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        $from = $edited_message->getFrom();
        $chat = $edited_message->getChat();

        $chat_id = $chat->getId();

        $edit_date = self::getTimestamp($edited_message->getEditDate());

        $entities = $edited_message->getEntities();

        //Insert chat
        self::insertChat($chat, $edit_date);

        //Insert user and the relation with the chat
        self::insertUser($from, $edit_date, $chat);

        try {
            $sth = self::$pdo->prepare('INSERT INTO `' . TB_EDITED_MESSAGE . '`
                (
                `chat_id`, `message_id`, `user_id`, `edit_date`, `text`, `entities`, `caption`
                )
                VALUES (
                :chat_id, :message_id, :user_id, :date, :text, :entities, :caption
                )');

            $message_id = $edited_message->getMessageId();
            $from_id    = $from->getId();

            $text    = $edited_message->getText();
            $caption = $edited_message->getCaption();

            $sth->bindParam(':chat_id', $chat_id, PDO::PARAM_INT);
            $sth->bindParam(':message_id', $message_id, PDO::PARAM_INT);
            $sth->bindParam(':user_id', $from_id, PDO::PARAM_INT);
            $sth->bindParam(':date', $edit_date, PDO::PARAM_STR);

            $var = [];
            if (is_array($entities)) {
                foreach ($entities as $elm) {
                    $var[] = json_decode($elm, true);
                }

                $entities = json_encode($var);
            } else {
                $entities = null;
            }

            $sth->bindParam(':text', $text, PDO::PARAM_STR);
            $sth->bindParam(':entities', $entities, PDO::PARAM_STR);
            $sth->bindParam(':caption', $caption, PDO::PARAM_STR);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Select Group and/or single Chats
     *
     * @param bool   $select_groups
     * @param bool   $select_super_groups
     * @param bool   $select_users
     * @param string $date_from
     * @param string $date_to
     * @param int    $chat_id
     * @param string $text
     *
     * @return array|bool (Selected chats or false if invalid arguments)
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public static function selectChats(
        $select_groups = true,
        $select_super_groups = true,
        $select_users = true,
        $date_from = null,
        $date_to = null,
        $chat_id = null,
        $text = null
    ) {
        if (!self::isDbConnected()) {
            return false;
        }

        if (!$select_groups && !$select_users && !$select_super_groups) {
            return false;
        }

        try {
            $query = 'SELECT * ,
                ' . TB_CHAT . '.`id` AS `chat_id`,
                ' . TB_CHAT . '.`created_at` AS `chat_created_at`,
                ' . TB_CHAT . '.`updated_at` AS `chat_updated_at`
                ' .
                (($select_users) ? ', ' . TB_USER . '.`id` AS `user_id` FROM `' . TB_CHAT . '` LEFT JOIN `' . TB_USER . '`
                ON ' . TB_CHAT . '.`id`=' . TB_USER . '.`id`' : 'FROM `' . TB_CHAT . '`');

            //Building parts of query
            $where  = [];
            $tokens = [];

            if (!$select_groups || !$select_users || !$select_super_groups) {
                $chat_or_user = '';

                if ($select_groups) {
                    $chat_or_user .= TB_CHAT . '.`type` = "group"';
                }

                if ($select_super_groups) {
                    if (!empty($chat_or_user)) {
                        $chat_or_user .= ' OR ';
                    }

                    $chat_or_user .= TB_CHAT . '.`type` = "supergroup"';
                }

                if ($select_users) {
                    if (!empty($chat_or_user)) {
                        $chat_or_user .= ' OR ';
                    }

                    $chat_or_user .= TB_CHAT . '.`type` = "private"';
                }

                $where[] = '(' . $chat_or_user . ')';
            }

            if (!is_null($date_from)) {
                $where[]              = TB_CHAT . '.`updated_at` >= :date_from';
                $tokens[':date_from'] = $date_from;
            }

            if (!is_null($date_to)) {
                $where[]            = TB_CHAT . '.`updated_at` <= :date_to';
                $tokens[':date_to'] = $date_to;
            }

            if (!is_null($chat_id)) {
                $where[]            = TB_CHAT . '.`id` = :chat_id';
                $tokens[':chat_id'] = $chat_id;
            }

            if (!is_null($text)) {
                if ($select_users) {
                    $where[] = '(LOWER(' . TB_CHAT . '.`title`) LIKE :text OR LOWER(' . TB_USER . '.`first_name`) LIKE :text OR LOWER(' . TB_USER . '.`last_name`) LIKE :text OR LOWER(' . TB_USER . '.`username`) LIKE :text)';
                } else {
                    $where[] = 'LOWER(' . TB_CHAT . '.`title`) LIKE :text';
                }

                $tokens[':text'] = '%' . strtolower($text) . '%';
            }

            $a = 0;
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

            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        return $result;
    }
}
