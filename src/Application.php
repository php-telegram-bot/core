<?php
declare(strict_types=1);

namespace Longman\TelegramBot;

use Illuminate\Container\Container;
use Illuminate\Support\Str;

use const DIRECTORY_SEPARATOR;

class Application extends Telegram
{
    protected $basePath;
    protected $appPath;
    protected $environmentPath;
    protected $environmentFile = '.env';
    protected $hasBeenBootstrapped = false;

    public function __construct(string $basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();

        $bootstrappers = [
            \Longman\TelegramBot\Bootstrap\LoadEnvironmentVariables::class,
            \Longman\TelegramBot\Bootstrap\SetupDatabase::class,
            \Longman\TelegramBot\Bootstrap\SetupMigrations::class,
        ];

        $this->bootstrapWith($bootstrappers);

        parent::__construct(env('TG_API_KEY'), env('TG_BOT_NAME'));
    }

    protected function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
    }

    public function bootstrapWith(array $bootstrappers)
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    public function hasBeenBootstrapped(): bool
    {
        return $this->hasBeenBootstrapped;
    }

    public function setBasePath($basePath): Application
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }

    protected function bindPathsInContainer()
    {
        $this->instance('path', $this->path());
        $this->instance('path.base', $this->basePath());
        //$this->instance('path.config', $this->configPath());
    }

    public function path(string $path = ''): string
    {
        $appPath = $this->appPath ?: $this->basePath . DIRECTORY_SEPARATOR . 'app';

        return $appPath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function basePath(string $path = ''): string
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function environmentPath(): string
    {
        return $this->environmentPath ?: $this->basePath;
    }

    public function configPath(string $path = ''): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function bootstrapPath(string $path = ''): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'bootstrap' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function getCachedConfigPath(): string
    {
        return $_ENV['APP_CONFIG_CACHE'] ?? $this->bootstrapPath() . '/cache/config.php';
    }

    public function loadEnvironmentFrom($file): Application
    {
        $this->environmentFile = $file;

        return $this;
    }

    public function environmentFile(): string
    {
        return $this->environmentFile ?: '.env';
    }

    public function environmentFilePath(): string
    {
        return $this->environmentPath() . DIRECTORY_SEPARATOR . $this->environmentFile();
    }

    public function environment(...$environments)
    {
        if (count($environments) > 0) {
            $patterns = is_array($environments[0]) ? $environments[0] : $environments;

            return Str::is($patterns, $this['env']);
        }

        return $this['env'];
    }

    public function isLocal(): bool
    {
        return $this['env'] === 'local';
    }
}
