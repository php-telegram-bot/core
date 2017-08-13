<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_chat', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';            $table->enum('type', ['private','group','supergroup','channel']);
            $table->string('title', 255)->default('');
            $table->string('username', 255)->nullable();
            $table->tinyInteger('all_members_are_administrators')->default(0);
            $table->bigInteger('old_id')->nullable();
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
        Schema::dropIfExists('telegram_chat');
    }
}
