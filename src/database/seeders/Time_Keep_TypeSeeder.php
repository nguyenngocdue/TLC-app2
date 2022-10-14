<?php

namespace Database\Seeders;

use App\Models\User_time_keep_type;
use Illuminate\Database\Seeder;

class Time_Keep_TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User_time_keep_type::factory()
            ->count(1)
            ->create();
    }
}
