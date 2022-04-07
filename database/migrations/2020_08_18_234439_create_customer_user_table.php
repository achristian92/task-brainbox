<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_user');
    }
}
