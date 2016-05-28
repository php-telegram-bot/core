<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot;

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * Class ConversationDB
 */
class ConversationDB extends DB
{
    /**
     * Initilize conversation table
     */
    public static function initializeConversation()
    {
        if (!defined('TB_CONVERSATION')) {
            define('TB_CONVERSATION', self::$table_prefix . 'conversation');
        }
    }

    /**
     * Select a conversation from the DB
     *
     * @param int  $user_id
     * @param int  $chat_id
     * @param bool $limit
     *
     * @return array|bool
     */
    public static function selectConversation($user_id, $chat_id, $limit = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $query = 'SELECT * FROM `' . TB_CONVERSATION . '` ';
            $query .= 'WHERE `status` = :status ';
            $query .= 'AND `chat_id` = :chat_id ';
            $query .= 'AND `user_id` = :user_id ';

            $tokens = [':chat_id' => $chat_id, ':user_id' => $user_id];
            if (!is_null($limit)) {
                $query .= ' LIMIT :limit';
            }
            $sth = self::$pdo->prepare($query);

            $active = 'active';
            $sth->bindParam(':status', $active, \PDO::PARAM_STR);
            $sth->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $sth->bindParam(':chat_id', $chat_id, \PDO::PARAM_INT);
            $sth->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $sth->execute();

            $results = $sth->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new TelegramException($e->getMessage());
        }
        return $results;
    }

    /**
     * Insert the conversation in the database
     *
     * @param int    $user_id
     * @param int    $chat_id
     * @param string $command
     *
     * @return bool
     */
    public static function insertConversation($user_id, $chat_id, $command)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('INSERT INTO `' . TB_CONVERSATION . '`
                (
                `status`, `user_id`, `chat_id`, `command`, `notes`, `created_at`, `updated_at`
                )
                VALUES (
                :status, :user_id, :chat_id, :command, :notes, :date, :date
                )
               ');
            $active = 'active';
            //$notes = json_encode('');
            $notes = '""';
            $created_at = self::getTimestamp();

            $sth->bindParam(':status', $active);
            $sth->bindParam(':command', $command);
            $sth->bindParam(':user_id', $user_id);
            $sth->bindParam(':chat_id', $chat_id);
            $sth->bindParam(':notes', $notes);
            $sth->bindParam(':date', $created_at);

            $status = $sth->execute();
        } catch (\Exception $e) {
            throw new TelegramException($e->getMessage());
        }
        return $status;
    }

    /**
     * Update a specific conversation
     *
     * @param array $fields_values
     * @param array $where_fields_values
     *
     * @return bool
     */
    public static function updateConversation(array $fields_values, array $where_fields_values)
    {
        return self::update(TB_CONVERSATION, $fields_values, $where_fields_values);
    }

    /**
     * Update the conversation in the database
     *
     * @param string $table
     * @param array  $fields_values
     * @param array  $where_fields_values
     *
     * @todo This function is generic should be moved in DB.php
     *
     * @return bool
     */
    public static function update($table, array $fields_values, array $where_fields_values)
    {
        if (!self::isDbConnected()) {
            return false;
        }
        //Auto update the field update_at
        $fields_values['updated_at'] = self::getTimestamp();

        //Values
        $update = '';
        $tokens = [];
        $tokens_counter = 0;
        $a = 0;
        foreach ($fields_values as $field => $value) {
            if ($a) {
                $update .= ', ';
            }
            ++$a;
            ++$tokens_counter;
            $update .= '`' . $field . '` = :' . $tokens_counter;
            $tokens[':' . $tokens_counter] = $value;
        }

        //Where
        $a = 0;
        $where  = '';
        foreach ($where_fields_values as $field => $value) {
            if ($a) {
                $where .= ' AND ';
            } else {
                ++$a;
                $where .= 'WHERE ';
            }
            ++$tokens_counter;
            $where .= '`' . $field .'`= :' . $tokens_counter;
            $tokens[':' . $tokens_counter] = $value;
        }

        $query = 'UPDATE `' . $table . '` SET ' . $update . ' ' . $where;
        try {
            $sth = self::$pdo->prepare($query);
            $status = $sth->execute($tokens);
        } catch (\Exception $e) {
            throw new TelegramException($e->getMessage());
        }
        return $status;
    }
}
