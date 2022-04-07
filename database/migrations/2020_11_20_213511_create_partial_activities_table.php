<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartialActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partial_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('duration');
            $table->dateTime('completed_at');

            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partial_activities');
    }
}
