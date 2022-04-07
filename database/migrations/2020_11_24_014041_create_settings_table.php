<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); 
            $table->string('company');
            $table->string('send_email');
            $table->string('project')->default('TASK-IQ');
            $table->double('hours')->default(172)->nullable();
            $table->boolean('send_overdue')->default(true);
            $table->boolean('send_credentials')->default(true);
            $table->boolean('notify_deadline')->default(true);
            $table->string('url_logo');
            $table->string('favicon');

        });

    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
