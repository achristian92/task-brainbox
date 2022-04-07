<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_planned')->default(true);
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('user_id');
            $table->string('previous_id')->nullable();
            $table->string('name');
            $table->string('time_estimate')->default('00:00');
            $table->longText('description')->nullable();
            $table->date('start_date');
            $table->date('due_date');
            $table->date('deadline')->nullable();
            $table->string('time_real')->default('00:00')->nullable();
            $table->longText('description2')->nullable();
            $table->string('status')->default('planned');
            $table->dateTime('completed_date')->nullable();
            $table->unsignedInteger('created_by_id');
            $table->dateTime('created_date')->nullable();
            $table->unsignedInteger('updated_by_id')->nullable();
            $table->dateTime('updated_date')->nullable();
            $table->unsignedInteger('approved_by_id')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->boolean('is_priority')->default(0);
            $table->boolean('is_partial')->default(0);
            $table->unsignedInteger('tag_id')->nullable();
            $table->boolean('is_assign')->default(0);
            $table->boolean('is_reassign')->default(0);
            $table->boolean('notified')->default(0);
            $table->boolean('with_subactivities')->default(0);
            $table->boolean('different_completed_date')->default(false);
            $table->date('completed_date_manual')->nullable();
            $table->boolean('is_approved_change_date')->default(false);
            $table->unsignedInteger('approved_change_date_by')->nullable();
            $table->date('approved_change_date')->nullable();
            $table->timestamps();

//            $table->foreign('customer_id')->references('id')->on('customers');
//            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
