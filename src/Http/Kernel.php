<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Http;

use Longman\TelegramBot\Entities\Update;
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
     * @return \Longman\TelegramBot\Http\Response
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handle(Request $request)
    {
        $params = $request->json();

        $update = new Update($params->all(), $this->app->getBotUsername());

        if ($response = $this->app->processUpdate($update)) {
            return $response->isOk();
        }

        return $response;
    }
}
