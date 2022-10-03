<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'read_media']);
        Permission::create(['name' => 'create_media']);
        Permission::create(['name' => 'edit_media']);
        Permission::create(['name' => 'edit_others_media']);
        Permission::create(['name' => 'delete_media']);
        Permission::create(['name' => 'delete_others_media']);
        Permission::create(['name' => 'read_post']);
        Permission::create(['name' => 'create_post']);
        Permission::create(['name' => 'edit_post']);
        Permission::create(['name' => 'edit_others_post']);
        Permission::create(['name' => 'delete_post']);
        Permission::create(['name' => 'delete_others_post']);
        Permission::create(['name' => 'read_workplace']);
        Permission::create(['name' => 'create_workplace']);
        Permission::create(['name' => 'edit_workplace']);
        Permission::create(['name' => 'edit_others_workplace']);
        Permission::create(['name' => 'delete_workplace']);
        Permission::create(['name' => 'delete_others_workplace']);
        Permission::create(['name' => 'read_user']);
        Permission::create(['name' => 'create_user']);
        Permission::create(['name' => 'edit_user']);
        Permission::create(['name' => 'edit_others_user']);
        Permission::create(['name' => 'delete_user']);
        Permission::create(['name' => 'delete_others_user']);
    }
}
