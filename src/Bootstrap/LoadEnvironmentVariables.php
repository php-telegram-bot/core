<?php
declare(strict_types=1);

namespace Longman\TelegramBot\Bootstrap;

use Dotenv\Dotenv;
use Dotenv\Environment\Adapter\EnvConstAdapter;
use Dotenv\Environment\Adapter\PutenvAdapter;
use Dotenv\Environment\Adapter\ServerConstAdapter;
use Dotenv\Environment\DotenvFactory;
use Exception;
use Longman\TelegramBot\Application;

class LoadEnvironmentVariables
{
    public function bootstrap(Application $app)
    {
        try {
            $this->createDotenv($app)->load();
        } catch (Exception $e) {
            $this->writeErrorAndDie($e);
        }
    }

    protected function createDotenv(Application $app): Dotenv
    {
        return Dotenv::create(
            $app->environmentPath(),
            $app->environmentFile(),
            new DotenvFactory([new EnvConstAdapter, new ServerConstAdapter, new PutenvAdapter])
        );
    }

    protected function writeErrorAndDie(Exception $e)
    {
        $message = 'The environment file is invalid! ' . $e->getMessage();
        die($message);
    }
}
