<?php
/**
 * Created by PhpStorm.
 * User: marijnkok
 * Date: 13/08/2017
 * Time: 12:55
 */

namespace Longman\TelegramBot;


class LongmanServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }
}