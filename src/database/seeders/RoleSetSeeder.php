<?php

namespace Database\Seeders;

use App\Models\RoleSet;
use Illuminate\Database\Seeder;

class RoleSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoleSet::create(['name' => 'admin'])->giveRoleTo(['ADMIN-DATA-MEDIA', 'ADMIN-DATA-POSTS', 'ADMIN-DATA-WORKPLACES']);
    }
}
