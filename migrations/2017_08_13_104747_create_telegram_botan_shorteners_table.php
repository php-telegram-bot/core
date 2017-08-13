<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramBotanShortenersTable extends Migration
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
        Schema::create($this->prefix . 'botan_shortener', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';
            $table->bigInteger('id');
            $table->bigInteger('user_id');
            $table->text('url');
            $table->string('short_url', 255)->default('');
            $table->timestamps();

            $table->primary('id');
            $table->index('user_id');
        });

        Schema::table($this->prefix . 'botan_shortener', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on($this->prefix . 'user');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->prefix . 'botan_shortener');
    }
}
