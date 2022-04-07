<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('ruc')->nullable();
            $table->double('hours')->nullable();
            $table->boolean('notify_excess_time')->default(false);
            $table->integer('num_transactions')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->string('contact_email')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
