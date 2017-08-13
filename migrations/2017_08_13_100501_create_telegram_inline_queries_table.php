<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramInlineQueriesTable extends Migration
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
        Schema::create($this->prefix . 'inline_query', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_520_ci';
            $table->bigInteger('id');
            $table->bigInteger('user_id')->nullable();
            $table->string('location', 255)->nullable();
            $table->text('query');
            $table->string('offset', 255)->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->index('user_id');
        });

        Schema::table($this->prefix . 'inline_query', function(Blueprint $table) {
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
        Schema::dropIfExists($this->prefix . 'inline_query');
    }
}
