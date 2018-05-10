<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTUserLoggedInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_logged_info', function (Blueprint $table) {
            $table->bigIncrements('user_logged_info_id');
            $table->bigInteger('user_id');
            $table->string('user_ip', 50);
            $table->string('user_device', 300);
            $table->string('user_browser', 300);
            $table->string('user_token', 100)->unique();
            $table->bigInteger('user_current_role_id');
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->bigInteger('version');
            $table->string('active', 1);
            $table->string('active_datetime', 14);
            $table->string('non_active_datetime', 14);
            $table->unique(['user_id', 'user_ip', 'user_device', 'user_browser']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_user_logged_info');
    }
}
