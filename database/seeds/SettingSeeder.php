<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            'company' => 'JGA CONSULTORES',
            'send_email' => 'no-replay@brainbox.pe',
            'project' => 'TASK-JGA',
            'url_logo' => 'https://brainbox20201126.s3.amazonaws.com/general/vP64JKUauUvDQ6vTEST-01-JGA.png',
            'favicon' => 'https://brainbox20201126.s3.amazonaws.com/general/nuGHGkqpZEy1f3Wfavicon.ico',
        ]);
    }

}
