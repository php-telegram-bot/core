<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramUpdatesTable extends Migration
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
        Schema::create($this->prefix . 'telegram_update', function (Blueprint $table) {
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

        Schema::table($this->prefix . 'telegram_', function(Blueprint $table) {
            $table->foreign(['chat_id','message_id'])->references(['chat_id', 'id'])->on($this->prefix . 'message');
            $table->foreign('inline_query_id')->references('id')->on($this->prefix . 'inline_query');
            $table->foreign('chosen_inline_result_id')->references('id')->on($this->prefix . 'chosen_inline_result');
            $table->foreign('callback_query_id')->references('id')->on($this->prefix . 'callback_query');
            $table->foreign('edited_message_id')->references('id')->on($this->prefix . 'edited_message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->prefix . 'telegram_');
    }
}
