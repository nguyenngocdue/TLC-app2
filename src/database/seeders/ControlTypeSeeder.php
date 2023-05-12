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
        Control_type::create(['name' => 'text', 'owner_id' => 1, 'slug' => 'text']);
        Control_type::create(['name' => 'textarea', 'owner_id' => 1, 'slug' => 'textarea']);
        Control_type::create(['name' => 'checkbox', 'owner_id' => 1, 'slug' => 'checkbox']);
        Control_type::create(['name' => 'radio', 'owner_id' => 1, 'slug' => 'radio']);
        Control_type::create(['name' => 'datetime', 'owner_id' => 1, 'slug' => 'datetime']);
        Control_type::create(['name' => 'dropdown', 'owner_id' => 1, 'slug' => 'dropdown']);
        Control_type::create(['name' => 'signature', 'owner_id' => 1, 'slug' => 'signature']);
    }
}
