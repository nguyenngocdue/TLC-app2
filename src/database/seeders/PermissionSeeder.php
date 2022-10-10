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
        Permission::create(['name' => 'read-media']);
        Permission::create(['name' => 'create-media']);
        Permission::create(['name' => 'edit-media']);
        Permission::create(['name' => 'edit-others-media']);
        Permission::create(['name' => 'delete-media']);
        Permission::create(['name' => 'delete-others-media']);
        Permission::create(['name' => 'read-post']);
        Permission::create(['name' => 'create-post']);
        Permission::create(['name' => 'edit-post']);
        Permission::create(['name' => 'edit-others-post']);
        Permission::create(['name' => 'delete-post']);
        Permission::create(['name' => 'delete-others-post']);
        Permission::create(['name' => 'read-workplace']);
        Permission::create(['name' => 'create-workplace']);
        Permission::create(['name' => 'edit-workplace']);
        Permission::create(['name' => 'edit-others-workplace']);
        Permission::create(['name' => 'delete-workplace']);
        Permission::create(['name' => 'delete-others-workplace']);
        Permission::create(['name' => 'read-user']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'edit-others-user']);
        Permission::create(['name' => 'delete-user']);
        Permission::create(['name' => 'delete-others-user']);
    }
}
