<?php
declare(strict_types=1);

namespace Longman\TelegramBot\Bootstrap;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Events\Dispatcher;
use Longman\TelegramBot\Application;

use PDO;
use function array_filter;
use function env;
use function extension_loaded;

class SetupDatabase
{
    public function bootstrap(Application $app)
    {
        $config = $this->getConfig();
        $capsule = new Capsule($app);
        $capsule->addConnection($config);

        $capsule->setEventDispatcher(new Dispatcher($app));

        $capsule->setAsGlobal();

        // $capsule->bootEloquent();

        $app->instance('db', $capsule);

        $app->bind('db.connection', function ($app) {
            return $app['db']->connection();
        });

        $app->bind('db.resolver', function ($app) {
            $resolver = new ConnectionResolver(['default' => $app['db.connection']]);
            $resolver->setDefaultConnection('default');
            return $resolver;
        });

        $app->enableExternalMySql($capsule->getConnection()->getPdo());
    }

    private function getConfig(): array
    {
        return [
            'driver'         => 'mysql',
            'host'           => env('TG_DB_HOST', '127.0.0.1'),
            'port'           => env('TG_DB_PORT', '3306'),
            'database'       => env('TG_DB_DATABASE', 'forge'),
            'username'       => env('TG_DB_USERNAME', 'forge'),
            'password'       => env('TG_DB_PASSWORD', ''),
            'unix_socket'    => env('TG_DB_SOCKET', ''),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => env('TG_DB_PREFIX', ''),
            'prefix_indexes' => true,
            'strict'         => env('TG_DB_STRICT', false),
            'engine'         => null,
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ];
    }
}
