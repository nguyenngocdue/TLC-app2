<?php

namespace Database\Seeders;

use App\Models\Control_type;
use Illuminate\Database\Seeder;

class ControlTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Control_type::create(['name' => 'text', 'slug' => 'text']);
        Control_type::create(['name' => 'textarea', 'slug' => 'textarea']);
        Control_type::create(['name' => 'checkbox', 'slug' => 'checkbox']);
        Control_type::create(['name' => 'radio', 'slug' => 'radio']);
        Control_type::create(['name' => 'datetime', 'slug' => 'datetime']);
        Control_type::create(['name' => 'dropdown', 'slug' => 'dropdown']);
        Control_type::create(['name' => 'signature', 'slug' => 'signature']);
    }
}
