<?php
declare(strict_types=1);

namespace Longman\TelegramBot\Console;

use Illuminate\Console\Command;

use function array_merge;
use function collect;

use const DIRECTORY_SEPARATOR;

class MigrateCommand extends Command
{
    protected $signature = 'migrate
                {--path= : The path to the migrations files to be executed}
                {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                {--pretend : Dump the SQL queries that would be run}
                {--step : Force the migrations to be run so they can be rolled back individually}';

    protected $description = 'Run the database migrations';

    /** @var \Illuminate\Database\Migrations\Migrator */
    protected $migrator;

    public function handle(): void
    {
        $app = $this->getApplication()->getLaravel();
        $this->migrator = $app['migrator'];

        $this->prepareDatabase();

        $this->migrator->setOutput($this->output)
            ->run($this->getMigrationPaths(), [
                'pretend' => $this->option('pretend'),
                'step'    => $this->option('step'),
            ]);
    }

    protected function prepareDatabase(): void
    {
        $this->migrator->setConnection('default');

        if (! $this->migrator->repositoryExists()) {
            $this->call('migrate:install');
        }
    }

    protected function getMigrationPaths(): array
    {
        // Here, we will check to see if a path option has been defined. If it has we will
        // use the path relative to the root of the installation folder so our database
        // migrations may be run for any customized path from within the application.
        if ($this->input->hasOption('path') && $this->option('path')) {
            return collect($this->option('path'))->map(function ($path) {
                return ! $this->usingRealPath() ? $this->laravel->basePath() . '/' . $path : $path;
            })->all();
        }

        return array_merge(
            $this->migrator->paths(),
            [$this->getMigrationPath()]
        );
    }

    protected function usingRealPath(): bool
    {
        return $this->input->hasOption('realpath') && $this->option('realpath');
    }

    protected function getMigrationPath(): string
    {
        return $this->laravel->basePath() . DIRECTORY_SEPARATOR . 'migrations';
    }
}
