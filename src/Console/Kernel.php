<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Console;

use Longman\TelegramBot\DB;
use Longman\TelegramBot\Http\Client;
use Longman\TelegramBot\Http\Request;
use Longman\TelegramBot\Http\Response;
use Longman\TelegramBot\Telegram;

class Kernel
{
    /**
     * The application implementation.
     *
     * @var \Longman\TelegramBot\Telegram
     */
    protected $app;

    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Longman\TelegramBot\Telegram $app
     *
     * @return void
     */
    public function __construct(Telegram $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming HTTP request.
     *
     * @param  \Longman\TelegramBot\Http\Request $request
     *
     * @param null $limit
     * @param null $timeout
     * @return \Longman\TelegramBot\Http\Response
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handle(Request $request, $limit = null, $timeout = null)
    {
        if (! DB::isDbConnected() && ! $this->app->getupdates_without_database) {
            return new Response(
                [
                    'ok'          => false,
                    'description' => 'getUpdates needs MySQL connection! (This can be overridden - see documentation)',
                ],
                $this->app->getBotUsername()
            );
        }

        $offset = 0;

        if (DB::isDbConnected()) {
            // Get last update id from the database
            $last_update = DB::selectTelegramUpdate(1);
            $last_update = reset($last_update);

            $this->app->last_update_id = isset($last_update['id']) ? $last_update['id'] : null;
        }

        if ($this->app->last_update_id !== null) {
            $offset = $this->app->last_update_id + 1;    //As explained in the telegram bot API documentation
        }

        $response = Client::getUpdates(
            [
                'offset'  => $offset,
                'limit'   => $limit,
                'timeout' => $timeout,
            ]
        );

        if ($response->isOk()) {
            $results = $response->getResult();

            // Process all updates
            /** @var \Longman\TelegramBot\Entities\Update $result */
            foreach ($results as $result) {
                $this->app->processUpdate($result);
            }

            if (! DB::isDbConnected() && ! $custom_input && $this->app->last_update_id !== null && $offset === 0) {
                // Mark update(s) as read after handling
                Client::getUpdates(
                    [
                        'offset'  => $this->app->last_update_id + 1,
                        'limit'   => 1,
                        'timeout' => $timeout,
                    ]
                );
            }
        }

        return $response;
    }
}
