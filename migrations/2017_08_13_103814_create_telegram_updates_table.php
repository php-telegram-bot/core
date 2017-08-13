<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_update', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';
            $table->bigInteger('id');
            $table->bigInteger('message_id');
            $table->bigInteger('chat_id');
            $table->bigInteger('inline_query_id');
            $table->bigInteger('chosen_inline_result_id');
            $table->bigInteger('callback_query_id');
            $table->bigInteger('edited_message_id');
            $table->timestamps();

            $table->primary('id');
            $table->index('message_id');
            $table->index('inline_query_id');
            $table->index('chosen_inline_result_id');
            $table->index('callback_query_id');
            $table->index('edited_message_id');
        });

        Schema::table('telegram_update', function(Blueprint $table) {
            $table->foreign(['chat_id','message_id'])->references(['chat_id', 'id'])->on('telegram_message');
            $table->foreign('inline_query_id')->references('id')->on('telegram_inline_query');
            $table->foreign('chosen_inline_result_id')->references('id')->on('telegram_chosen_inline_result');
            $table->foreign('callback_query_id')->references('id')->on('telegram_callback_query');
            $table->foreign('edited_message_id')->references('id')->on('telegram_edited_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_update');
    }
}
