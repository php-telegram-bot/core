<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramUsersTable extends Migration
{
    protected $prefix;

    public function __construct()
    {
        $this->prefix = config('longman.db_prefix');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->prefix . 'user', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';            $table->string('first_name', 255);
            $table->string('last_name', 255)->nullable();
            $table->string('username', 191)->nullable();
            $table->string('language_code', 10)->nullable();
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->prefix . 'user');
    }
}
