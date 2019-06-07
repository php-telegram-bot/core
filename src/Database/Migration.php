<?php
declare(strict_types=1);

namespace Longman\TelegramBot\Database;

use Illuminate\Database\Migrations\Migration as BaseMigration;
use Illuminate\Database\Schema\Builder;

use function app;

abstract class Migration extends BaseMigration
{
    public function getSchemaBuilder(): Builder
    {
        return app('db')->connection($this->getConnection())->getSchemaBuilder();
    }
}
