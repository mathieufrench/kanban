<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            'user_id' => 1,
            'title' => "Todo",
        ]);

        DB::table('users')->insert([
            'user_id' => 1,
            'title' => "In Progress",
        ]);

        DB::table('users')->insert([
            'user_id' => 1,
            'title' => "Done",
        ]);
    }
}
