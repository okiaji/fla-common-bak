<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTUserAdditionalInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_additional_info', function (Blueprint $table) {
            $table->bigIncrements('user_additional_info_id');
            $table->bigInteger('user_id')->unique();
            $table->bigInteger('user_type_id');
            $table->string('phone_number', 50);
            $table->string('religion', 50);
            $table->string('date_of_birth', 8);
            $table->string('place_of_birth', 100);
            $table->string('country', 100);
            $table->string('full_address', 1024);
            $table->string('create_datetime', 14);
            $table->string('update_datetime', 14);
            $table->bigInteger('create_user_id');
            $table->bigInteger('update_user_id');
            $table->bigInteger('version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_user_additional_info');
    }
}
