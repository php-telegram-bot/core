<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Longman\TelegramBot\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        $this->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->getSchemaBuilder()->dropIfExists('users');
    }
}
