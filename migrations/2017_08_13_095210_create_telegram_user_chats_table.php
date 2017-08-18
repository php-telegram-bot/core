<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramUserChatsTable extends Migration
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
        Schema::create($this->prefix . 'user_chat', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';
            $table->bigInteger('user_id');
            $table->bigInteger('chat_id');

            $table->primary(['user_id','chat_id']);
        });

        Schema::table($this->prefix . 'user_chat', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on($this->prefix . 'user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('chat_id')->references('id')->on($this->prefix . 'chat')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->prefix . 'user_chat');
    }
}
