<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTSecureQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_secure_question', function (Blueprint $table) {
            $table->bigIncrements('secure_question_id');
            $table->bigInteger('user_id');
            $table->string('secure_question', 100);
            $table->string('secure_answer', 1024);
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->bigInteger('version');
            $table->unique(['user_id', 'secure_question']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_secure_question');
    }
}
