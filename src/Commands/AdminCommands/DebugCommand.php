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
use Longman\TelegramBot\Request;

/**
 * Admin "/debug" command
 */
class DebugCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'debug';

    /**
     * @var string
     */
    protected $description = 'Debug command to help find issues';

    /**
     * @var string
     */
    protected $usage = '/debug';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $pdo = DB::getPdo();
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $debug_info = [];

        $debug_info[] = sprintf('_TelegramBot version_: `%s`', $this->telegram->getVersion());

        $php_bit = '';
        PHP_INT_SIZE === 4 && $php_bit = ' (32bit)';
        PHP_INT_SIZE === 8 && $php_bit = ' (64bit)';
        $debug_info[] = sprintf('_PHP version_: `%1$s%2$s; %3$s; %4$s`', PHP_VERSION, $php_bit, PHP_SAPI, PHP_OS);
        $debug_info[] = sprintf('_Maximum PHP script execution time_: `%d seconds`', ini_get('max_execution_time'));

        $mysql_version = $pdo ? $pdo->query('SELECT VERSION() AS version')->fetchColumn() : null;
        $debug_info[] = sprintf('_MySQL version_: `%s`', $mysql_version ?: 'disabled');

        $debug_info[] = sprintf('_Operating System_: `%s`', php_uname());

        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            $debug_info[] = sprintf('_Web Server_: `%s`', $_SERVER['SERVER_SOFTWARE']);
        }
        if (function_exists('curl_init')) {
            $curlversion = curl_version();
            $debug_info[] = sprintf('_curl version_: `%1$s; %2$s`', $curlversion['version'], $curlversion['ssl_version']);
        }

        $webhook_info_title = '_Webhook Info_:';
        try {
            // Check if we're actually using the Webhook method.
            if (Request::getInput() === '') {
                $debug_info[] = $webhook_info_title . ' `Using getUpdates method, not Webhook.`';
            } else {
                $webhook_info_result = json_encode(json_decode(Request::getWebhookInfo(), true)['result'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                $debug_info[] = $webhook_info_title;
                $debug_info[] = sprintf(
                    '```' . PHP_EOL . '%s```',
                    $webhook_info_result
                );
            }
        } catch (\Exception $e) {
            $debug_info[] = $webhook_info_title . sprintf(' `Failed to get webhook info! (%s)`', $e->getMessage());
        }

        $data = [
            'chat_id'    => $chat_id,
            'parse_mode' => 'Markdown',
            'text'       => implode(PHP_EOL, $debug_info),
        ];

        return Request::sendMessage($data);
    }
}
