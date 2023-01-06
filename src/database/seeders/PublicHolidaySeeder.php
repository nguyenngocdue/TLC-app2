<?php

namespace Database\Seeders;

use App\Models\Public_holiday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicHolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Public_holiday::create(['ph_date' => '2023-01-02', 'name' => 'test01', 'year' => 2023, 'workplace_id' => 2,  'ph_hours' => 8]);
        Public_holiday::create(['ph_date' => '2023-01-05', 'name' => 'test02', 'year' => 2023, 'workplace_id' => 2,  'ph_hours' => 8]);
        Public_holiday::create(['ph_date' => '2023-01-09', 'name' => 'test03', 'year' => 2023, 'workplace_id' => 2,  'ph_hours' => 8]);
        Public_holiday::create(['ph_date' => '2023-01-16', 'name' => 'test04', 'year' => 2023, 'workplace_id' => 2,  'ph_hours' => 8]);
        Public_holiday::create(['ph_date' => '2023-01-23', 'name' => 'test05', 'year' => 2023, 'workplace_id' => 2,  'ph_hours' => 8]);
    }
}
