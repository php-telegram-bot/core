<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\AdminCommands;

use Longman\TelegramBot\Commands\AdminCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\TelegramLog;
use PDOException;

/**
 * User "/cleanup" command
 */
class CleanupCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'cleanup';

    /**
     * @var string
     */
    protected $description = 'Clean up the database from old records';

    /**
     * @var string
     */
    protected $usage = '/cleanup';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Set command config
     *
     * @param string $custom_time
     *
     * @return array
     */
    private function getSettings($custom_time = '')
    {
        // default tables to clean, cleaning 'chat', 'user' and 'user_chat' will be a bad practice!
        $tables_to_clean = [
            'botan_shortener',
            'callback_query',
            'chosen_inline_result',
            'conversation',
            'edited_message',
            'inline_query',
            'message',
            'request_limiter',
            'telegram_update',
        ];

        // remove records from these tables older than these X days/hours/anything
        $time_to_clean = [
            'botan_shortener'      => '30 days',
            'chat'                 => '365 days',
            'callback_query'       => '30 days',
            'chosen_inline_result' => '30 days',
            'conversation'         => '30 days',
            'edited_message'       => '30 days',
            'inline_query'         => '30 days',
            'message'              => '30 days',
            'request_limiter'      => '1 minute',
            'telegram_update'      => '30 days',
            'user'                 => '365 days',
            'user_chat'            => '365 days',
        ];

        $user_tables_to_clean = $this->getConfig('tables_to_clean');
        if (!is_null($user_tables_to_clean)) {
            if (!is_array($user_tables_to_clean)) {
                throw new TelegramException('Variable \'tables_to_clean\' must be an array!');
            }

            $tables_to_clean = $user_tables_to_clean;
        }

        $user_time_to_clean = $this->getConfig('time_to_clean');
        if (!is_null($user_tables_to_clean)) {
            if (!$this->isAssociativeArray($user_time_to_clean)) {
                throw new TelegramException('Variable \'time_to_clean\' must be an associative array!');
            }

            $time_to_clean = array_merge($time_to_clean, $user_time_to_clean);
        }

        $settings['tables_to_clean']    = $tables_to_clean;
        $settings['time_to_clean']      = $time_to_clean;

        if (is_numeric($custom_time)) {
            $custom_time = $custom_time . ' days';
        }

        foreach ($settings['tables_to_clean'] as $table_to_clean) {
            if (!empty($custom_time)) {
                $settings['time_to_clean'][$table_to_clean] = $custom_time;
            }
        }

        return $settings;
    }

    /**
     * Little function to return whenever array is associative or not
     *
     * @param array $arr
     *
     * @return bool
     */
    private function isAssociativeArray(array $arr)
    {
        if (!is_array($arr) || empty($arr)) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Get SQL queries array based on settings provided
     *
     * @param $settings
     *
     * @return array
     * @throws TelegramException
     */
    private function getQueries($settings)
    {
        if (empty($settings) || !is_array($settings)) {
            throw new TelegramException('Settings variable is not an array or is empty!');
        }

        $queries = [];

        if (in_array('telegram_update', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `telegram_update` WHERE
`id` != \'' . $this->getUpdate()->getUpdateId() . '\' AND
`chat_id` NOT IN (SELECT `id` FROM `chat` WHERE `chat_id` = `chat`.`id`) AND
(`message_id` IS NOT NULL AND `message_id` IN (SELECT f.id FROM `message` f WHERE `date` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['telegram_update'])) . '\')) OR
(`edited_message_id` IS NOT NULL AND `edited_message_id` IN (SELECT f.id FROM `edited_message` f WHERE `edit_date` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['telegram_update'])) . '\')) OR
(`inline_query_id` IS NOT NULL AND `inline_query_id` IN (SELECT f.id FROM `inline_query` f WHERE `created_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['telegram_update'])) . '\')) OR
(`chosen_inline_result_id` IS NOT NULL AND `chosen_inline_result_id` IN (SELECT f.id FROM `chosen_inline_result` f WHERE `created_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['telegram_update'])) . '\')) OR
(`callback_query_id` IS NOT NULL AND `callback_query_id` IN (SELECT f.id FROM `callback_query` f WHERE `created_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['telegram_update'])) . '\'))';
        }

        if (in_array('user_chat', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `user_chat` WHERE `user_id` IN (SELECT f.id FROM `user` f WHERE `updated_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['chat'])) . '\')' . PHP_EOL;
        }

        if (in_array('user', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `user` WHERE `updated_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['user'])) . '\'' . PHP_EOL;
        }

        if (in_array('chat', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `chat` WHERE `updated_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['chat'])) . '\'' . PHP_EOL;
        }

        if (in_array('inline_query', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `inline_query` WHERE `created_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['inline_query'])) . '\' AND `id` NOT IN (SELECT `inline_query_id` FROM `telegram_update` WHERE `inline_query_id` = `inline_query`.`id`)';
        }

        if (in_array('chosen_inline_result', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `chosen_inline_result` WHERE `created_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['chosen_inline_result'])) . '\' AND `id` NOT IN (SELECT `chosen_inline_result_id` FROM `telegram_update` WHERE `chosen_inline_result_id` = `chosen_inline_result`.`id`)';
        }

        if (in_array('callback_query', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `callback_query` WHERE `created_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['callback_query'])) . '\' AND `id` NOT IN (SELECT `callback_query_id` FROM `telegram_update` WHERE `callback_query_id` = `callback_query`.`id`)' . PHP_EOL;
        }

        if (in_array('edited_message', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `edited_message` WHERE `edit_date` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['edited_message'])) . '\' AND `id` NOT IN (SELECT `message_id` FROM `telegram_update` WHERE `edited_message_id` = `edited_message`.`id`)' . PHP_EOL;
        }

        if (in_array('message', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `message` WHERE `date` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['message'])) . '\' AND `id` NOT IN (SELECT `message_id` FROM `callback_query` WHERE `message_id` = `message`.`id`) AND `id` NOT IN (SELECT `message_id` FROM `telegram_update` WHERE `message_id` = `message`.`id`)' . PHP_EOL;
        }

        if (in_array('botan_shortener', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `botan_shortener` WHERE `created_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['botan_shortener'])) . '\'';
        }

        if (in_array('conversation', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `conversation` WHERE `updated_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['conversation'])) . '\'';
        }

        if (in_array('request_limiter', $settings['tables_to_clean'])) {
            $queries[] = 'DELETE FROM `request_limiter` WHERE `created_at` < \'' . date('Y-m-d H:i:s', strtotime('-' . $settings['time_to_clean']['request_limiter'])) . '\'';
        }

        return $queries;
    }

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getFrom()->getId();
        $text = $message->getText(true);

        $data = [];
        $data['chat_id'] = $chat_id;
        $data['parse_mode'] = 'Markdown';

        if (!$message->getChat()->isPrivateChat()) {
            $data['text'] = 'Only available in a private chat.';

            return Request::sendMessage($data);
        }

        $settings = $this->getSettings($text);
        $queries = $this->getQueries($settings);

        $tables = '';
        foreach ($settings['tables_to_clean'] as $table) {
            if (!empty($tables)) {
                $tables .= ', ';
            }

            $tables .= '*' . $table . '*';
            $time = $settings['time_to_clean'][$table];

            if (isset($time)) {
                $tables .= ' (' . $time . ')';
            }
        }

        $data['text'] = 'Cleaning up tables:' . PHP_EOL . ' ' . $tables;

        Request::sendMessage($data);

        $rows = 0;
        $pdo = DB::getPdo();
        try {
            $pdo->beginTransaction();

            foreach ($queries as $query) {
                $dbq = $pdo->prepare($query);
                if ($dbq->execute()) {
                    $rows += $dbq->rowCount();
                } else {
                    TelegramLog::error('Error while executing query: ' . $query . PHP_EOL);
                }
            }
        } catch (PDOException $e) {
            $pdo->rollBack();   // rollback changes on exception (useful if you want to track down error - you can't replicate it when some of the data is already deleted...)
            throw new TelegramException($e->getMessage());
        } finally {
            $pdo->commit();     // commit changes to the database and end transaction
        }

        if (isset($rows)) {
            if ($rows > 0) {
                $data['text'] = '*Database cleanup done!* _(removed ' . $rows .' rows)_';
            } else {
                $data['text'] = '*No data to clean!*';
            }
        } else {
            $data['text'] = '*Database cleanup failed!*';
        }

        return Request::sendMessage($data);
    }
}
