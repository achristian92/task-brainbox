<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupervisorUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisor_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('supervisor_id');
            $table->unsignedInteger('user_id');

            $table->foreign('supervisor_id')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supervisor_user');
    }
}
