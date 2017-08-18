<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramMessagesTable extends Migration
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
        Schema::create($this->prefix . 'message', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';
            $table->bigInteger('chat_id');
            $table->bigInteger('id');
            $table->bigInteger('user_id');
            $table->timestamp('date')->nullable();
            $table->bigInteger('forward_from')->nullable();
            $table->bigInteger('forward_from_chat')->nullable();
            $table->bigInteger('forward_from_message_id')->nullable();
            $table->timestamp('forward_date')->nullable();
            $table->bigInteger('reply_to_chat')->nullable();
            $table->bigInteger('reply_to_message')->nullable();

            $table->text('text');
            $table->text('entities');
            $table->text('audio');
            $table->text('document');
            $table->text('photo');
            $table->text('sticker');
            $table->text('video');
            $table->text('voice');
            $table->text('video_note');
            $table->text('contact');
            $table->text('location');
            $table->text('venue');
            $table->text('caption');
            $table->text('new_chat_members');

            $table->bigInteger('left_chat_member')->nullable();
            $table->string('new_chat_title', 255)->nullable();
            $table->text('new_chat_photo');
            $table->tinyInteger('delete_chat_photo')->default(0);
            $table->tinyInteger('group_chat_created')->default(0);
            $table->tinyInteger('supergroup_chat_created')->default(0);
            $table->tinyInteger('channel_chat_created')->default(0);
            $table->bigInteger('migrate_to_chat_id')->nullable();
            $table->bigInteger('migrate_from_chat_id')->nullable();
            $table->text('pinned_message')->nullable();

            $table->primary(['chat_id', 'id']);
            $table->index('user_id');
            $table->index('forward_from');
            $table->index('forward_from_chat');
            $table->index('reply_to_chat');
            $table->index('reply_to_message');
            $table->index('left_chat_member');
            $table->index('migrate_to_chat_id');
            $table->index('migrate_from_chat_id');

            $table->timestamps();
        });

        Schema::table($this->prefix . 'message', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on($this->prefix . 'user');
            $table->foreign('chat_id')->references('id')->on($this->prefix . 'chat');

            $table->foreign('forward_from')->references('id')->on($this->prefix . 'user');
            $table->foreign('forward_from_chat')->references('id')->on($this->prefix . 'chat');

            $table->foreign(['reply_to_chat','reply_to_message'])->references(['chat_id','id'])->on($this->prefix . 'message');
            $table->foreign('left_chat_member')->references('id')->on($this->prefix . 'user');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->prefix . 'message');
    }
}
