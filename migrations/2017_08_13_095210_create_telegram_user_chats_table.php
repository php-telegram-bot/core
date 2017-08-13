<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramUserChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_user_chat', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';
            $table->bigInteger('user_id');
            $table->bigInteger('chat_id');

            $table->primary(['user_id','chat_id']);
        });

        Schema::table('telegram_user_chat', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('telegram_user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('chat_id')->references('id')->on('telegram_chat')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_user_chat');
    }
}
