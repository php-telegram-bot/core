<?php
declare(strict_types=1);

namespace Longman\TelegramBot\Bootstrap;

use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Longman\TelegramBot\Application;

use function env;

class SetupMigrations
{
    public function bootstrap(Application $app)
    {
        $app->singleton('files', function (Application $app) {
            return new Filesystem();
        });

        $app->singleton('migration.repository', function (Application $app) {
            $table = env('TG_DB_MIGRATIONS_TABLE', 'migrations');

            return new DatabaseMigrationRepository($app['db.resolver'], $table);
        });

        $app->singleton('migrator', function (Application $app) {
            $repository = $app['migration.repository'];

            return new Migrator($repository, $app['db.resolver'], $app['files']);
        });

        $app->singleton('migration.creator', function (Application $app) {
            return new MigrationCreator($app['files']);
        });
    }
}
