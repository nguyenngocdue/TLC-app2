<?php

namespace Database\Seeders;

use App\Models\UserTimeKeepingType;
use Illuminate\Database\Seeder;

class TimeKeepingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserTimeKeepingType::factory()
            ->count(1)
            ->create();
    }
}
