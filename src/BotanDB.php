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

/**
 * Class BotanDB
 */
class BotanDB extends DB
{
    /**
     * Initialize botan shortener table
     */
    public static function initializeBotanDb()
    {
        if (!defined('TB_BOTAN_SHORTENER')) {
            define('TB_BOTAN_SHORTENER', self::$table_prefix . 'botan_shortener');
        }
    }

    /**
     * Select cached shortened URL from the database
     *
     * @param string $url
     * @param string $user_id
     *
     * @return array|bool
     * @throws TelegramException
     */
    public static function selectShortUrl($url, $user_id)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('
                SELECT `short_url`
                FROM `' . TB_BOTAN_SHORTENER . '`
                WHERE `user_id` = :user_id
                  AND `url` = :url
                ORDER BY `created_at` DESC
                LIMIT 1
            ');

            $sth->bindValue(':user_id', $user_id);
            $sth->bindValue(':url', $url);
            $sth->execute();

            return $sth->fetchColumn();
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert shortened URL into the database
     *
     * @param string $url
     * @param string $user_id
     * @param string $short_url
     *
     * @return bool
     * @throws TelegramException
     */
    public static function insertShortUrl($url, $user_id, $short_url)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('
                INSERT INTO `' . TB_BOTAN_SHORTENER . '`
                (`user_id`, `url`, `short_url`, `created_at`)
                VALUES
                (:user_id, :url, :short_url, :created_at)
            ');

            $sth->bindValue(':user_id', $user_id);
            $sth->bindValue(':url', $url);
            $sth->bindValue(':short_url', $short_url);
            $sth->bindValue(':created_at', self::getTimestamp());

            return $sth->execute();
        } catch (Exception $e) {
            throw new TelegramException($e->getMessage());
        }
    }
}
