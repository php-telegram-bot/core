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

use Exception;
use Longman\TelegramBot\Exception\TelegramException;
use PDO;

class ConversationDB extends DB
{
    /**
     * Initialize conversation table
     */
    public static function initializeConversation(): void
    {
        if (!defined('TB_CONVERSATION')) {
            define('TB_CONVERSATION', self::$table_prefix . 'conversation');
        }
    }

    /**
     * Select a conversation from the DB
     *
     * @param int $user_id
     * @param int $chat_id
     * @param int $limit
     *
     * @return array|bool
     * @throws TelegramException
     */
    public static function selectConversation(int $user_id, int $chat_id, $limit = 0)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sql = '
              SELECT *
              FROM `' . TB_CONVERSATION . '`
              WHERE `status` = :status
                AND `chat_id` = :chat_id
                AND `user_id` = :user_id
            ';

            if ($limit > 0) {
                $sql .= ' LIMIT :limit';
            }

            $sth = self::$pdo->prepare($sql);

            $sth->bindValue(':status', 'active');
            $sth->bindValue(':user_id', $user_id);
            $sth->bindValue(':chat_id', $chat_id);

            if ($limit > 0) {
                $sth->bindValue(':limit', $limit, PDO::PARAM_INT);
            }

            $sth->execute();

            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert the conversation in the database
     *
     * @param int    $user_id
     * @param int    $chat_id
     * @param string $command
     *
     * @return bool
     * @throws TelegramException
     */
    public static function insertConversation(int $user_id, int $chat_id, string $command): bool
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('INSERT INTO `' . TB_CONVERSATION . '`
                (`status`, `user_id`, `chat_id`, `command`, `notes`, `created_at`, `updated_at`)
                VALUES
                (:status, :user_id, :chat_id, :command, :notes, :created_at, :updated_at)
            ');

            $date = self::getTimestamp();

            $sth->bindValue(':status', 'active');
            $sth->bindValue(':command', $command);
            $sth->bindValue(':user_id', $user_id);
            $sth->bindValue(':chat_id', $chat_id);
            $sth->bindValue(':notes', '[]');
            $sth->bindValue(':created_at', $date);
            $sth->bindValue(':updated_at', $date);

            return $sth->execute();
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Update a specific conversation
     *
     * @param array $fields_values
     * @param array $where_fields_values
     *
     * @return bool
     * @throws TelegramException
     */
    public static function updateConversation(array $fields_values, array $where_fields_values): bool
    {
        // Auto update the update_at field.
        $fields_values['updated_at'] = self::getTimestamp();

        return self::update(TB_CONVERSATION, $fields_values, $where_fields_values);
    }
}
