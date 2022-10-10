<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'READ-DATA-MEDIA'])->givePermissionTo('read-media');
        Role::create(['name' => 'READ-WRITE-DATA-MEDIA'])->givePermissionTo(['read-media', 'edit-media', 'create-media', 'edit-others-media']);
        Role::create(['name' => 'ADMIN-DATA-MEDIA'])->givePermissionTo(['read-media', 'edit-media', 'create-media', 'edit-others-media', 'delete-media', 'delete-others-media']);

        Role::create(['name' => 'READ-DATA-POSTS'])->givePermissionTo('read-post');
        Role::create(['name' => 'READ-WRITE-DATA-POSTS'])->givePermissionTo(['read-post', 'edit-post', 'create-post', 'edit-others-post']);
        Role::create(['name' => 'ADMIN-DATA-POSTS'])->givePermissionTo(['read-post', 'edit-post', 'create-post', 'edit-others-post', 'delete-post', 'delete-others-post']);

        Role::create(['name' => 'READ-DATA-WORKPLACES'])->givePermissionTo('read-workplace');
        Role::create(['name' => 'READ-WRITE-DATA-WORKPLACES'])->givePermissionTo(['read-workplace', 'edit-workplace', 'create-workplace', 'edit-others-workplace']);
        Role::create(['name' => 'ADMIN-DATA-WORKPLACES'])->givePermissionTo(['read-workplace', 'edit-workplace', 'create-workplace', 'edit-others-workplace', 'delete-workplace', 'delete-others-workplace']);

        Role::create(['name' => 'READ-DATA-USERS'])->givePermissionTo('read-user');
        Role::create(['name' => 'READ-WRITE-DATA-USERS'])->givePermissionTo(['read-user', 'edit-user', 'create-user', 'edit-others-user']);
        Role::create(['name' => 'ADMIN-DATA-USERS'])->givePermissionTo(['read-user', 'edit-user', 'create-user', 'edit-others-user', 'delete-user', 'delete-others-user']);
    }
}
