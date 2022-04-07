<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_history', function (Blueprint $table) {
            $table->id();
            $table->string('user_full_name')->nullable();
            $table->string('type')->nullable();
            $table->text('description');
            $table->json('model')->nullable();
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('user_history');
    }
}
