<?php

namespace Database\Seeders;

use App\Models\Workplace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Workplace::factory()
            ->count(100)
            ->create();
    }
}
