<?php
declare(strict_types=1);

namespace Longman\TelegramBot\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class MigrateInstallCommand extends Command
{
    protected $signature = 'migrate:install';

    protected $description = 'Create the migration repository';

    public function handle(): void
    {
        $app = $this->getApplication()->getLaravel();

        $repository = new DatabaseMigrationRepository($app['db.resolver'], 'migrations');

        $repository->createRepository();

        $this->info('Migration table created successfully.');
    }
}

