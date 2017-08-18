<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramCallbackQueriesTable extends Migration
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
        Schema::create($this->prefix . 'callback_query', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';
            $table->bigInteger('id');
            $table->bigInteger('chat_id');
            $table->bigInteger('user_id');
            $table->bigInteger('message_id');
            $table->string('inline_message_id', 255)->nullable();
            $table->string('data', 255)->default('');
            $table->timestamps();

            $table->primary('id');
            $table->index('user_id');
            $table->index('chat_id');
            $table->index('message_id');
        });

        Schema::table($this->prefix . 'callback_query', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on($this->prefix . 'user');
            $table->foreign(['chat_id','message_id'])->references(['chat_id', 'id'])->on($this->prefix . 'message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->prefix . 'callback_query');
    }
}
