<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_role', function (Blueprint $table) {
            $table->bigIncrements('role_id');
            $table->string('role_code', 50)->unique();
            $table->string('role_name', 100);
            $table->string('role_desc', 1024);
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->bigInteger('version');
            $table->string('active', 1);
            $table->string('active_datetime', 14);
            $table->string('non_active_datetime', 14);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_role');
    }
}
