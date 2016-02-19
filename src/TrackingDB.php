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

/**
 * Class TrackingDB
 */
class TrackingDB extends DB
{
    /**
     * Initilize tracking table
     */
    public static function initializeTracking()
    {
        if (!defined('TB_TRACK')) {
            define('TB_TRACK', self::$table_prefix . 'track');
        }
    }
 
    /**
     * Tracking contructor initialize a new track
     *
     * @param int  $user_id
     * @param int  $chat_id
     * @param bool $limit
     *
     * @return array
     */
    public static function selectTrack($user_id, $chat_id, $limit = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }
 
        try {
            $query = 'SELECT * FROM `' . TB_TRACK . '` ';
            $query .= 'WHERE `is_active` = 1 ';
            $query .= 'AND `chat_id` = :chat_id ';
            $query .= 'AND `user_id` = :user_id ';
 
            $tokens = [':chat_id' => $chat_id, ':user_id' => $user_id];
            if (!is_null($limit)) {
                $query .=' LIMIT :limit';
            }
            $sth = self::$pdo->prepare($query);

            $sth->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $sth->bindParam(':chat_id', $chat_id, \PDO::PARAM_INT);
            $sth->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $sth->execute();

            $results = $sth->fetchAll(\PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
        return $results;
    }

    /**
     * Insert the track in the database
     *
     * @param string $track_command
     * @param string $track_name
     * @param int    $user_id
     * @param int    $chat_id
     *
     * @return bool
     */
    public static function insertTrack($track_command, $track_group_name, $user_id, $chat_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('INSERT INTO `' . TB_TRACK . '`
                (
                `is_active`, `track_command`, `track_name`, `user_id`, `chat_id`, `data`, `created_at`, `updated_at`
                )
                VALUES (
                :is_active, :track_command, :track_name, :user_id, :chat_id, :data, :date, :date
                )
               ');

            $active = 1;
            $data = json_encode('');
            $created_at = self::getTimestamp();

            $sth->bindParam(':is_active', $active);
            $sth->bindParam(':track_command', $track_command);
            $sth->bindParam(':track_name', $track_group_name);
            $sth->bindParam(':user_id', $user_id);
            $sth->bindParam(':chat_id', $chat_id);
            $sth->bindParam(':data', $data);
            $sth->bindParam(':date', $created_at);

            $status = $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
        return $status;
    }

    /**
     * Update a specific track
     *
     * @param array $fields_values
     * @param array $where_fields_values
     *
     * @return bool
     */
    public static function updateTrack(array $fields_values, array $where_fields_values)
    {
        return self::update(TB_TRACK, $fields_values, $where_fields_values);
    }

    /**
     * Insert the track in the database
     *
     * @param string   $table
     * @param array    $fields_values
     * @param array    $where_fields_values
     *
     * @todo this function is generic should be moved in DB.php
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
        $a = 0;
        foreach ($fields_values as $field => $value) {
            if ($a) {
                $update .= ', ';
            }
            ++$a;
            $update .= '`'.$field.'` = :'.$a;
            $tokens[':'.$a] = $value;
        }

        //Where
        $a = 0;
        $where  = '';
        foreach ($where_fields_values as $field => $value) {
            if ($a) {
                $where  .= ' AND ';
            } else {
                ++$a;
                $where  .= 'WHERE ';
            }
            $where  .= '`'.$field .'`='.$value ;
        }

        $query = 'UPDATE `'.$table.'` SET '.$update.' '.$where;
        try {
            $sth = self::$pdo->prepare($query);
            $status = $sth->execute($tokens);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
        return $status;
    }
}
