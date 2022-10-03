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
        Role::create(['name' => 'READ-DATA-MEDIA'])->givePermissionTo('read_media');
        Role::create(['name' => 'READ-WRITE-DATA-MEDIA'])->givePermissionTo(['read_media', 'edit_media', 'create_media', 'edit_other_media']);
        Role::create(['name' => 'ADMIN-DATA-MEDIA'])->givePermissionTo(['read_media', 'edit_media', 'create_media', 'edit_other_media', 'delete_media', 'delete_other_media']);

        Role::create(['name' => 'READ-DATA-POSTS'])->givePermissionTo('read_post');
        Role::create(['name' => 'READ-WRITE-DATA-POSTS'])->givePermissionTo(['read_post', 'edit_post', 'create_post', 'edit_other_post']);
        Role::create(['name' => 'ADMIN-DATA-POSTS'])->givePermissionTo(['read_post', 'edit_post', 'create_post', 'edit_other_post', 'delete_post', 'delete_other_post']);

        Role::create(['name' => 'READ-DATA-WORKPLACES'])->givePermissionTo('read_workplace');
        Role::create(['name' => 'READ-WRITE-DATA-WORKPLACES'])->givePermissionTo(['read_workplace', 'edit_workplace', 'create_workplace', 'edit_other_workplace']);
        Role::create(['name' => 'ADMIN-DATA-WORKPLACES'])->givePermissionTo(['read_workplace', 'edit_workplace', 'create_workplace', 'edit_other_workplace', 'delete_workplace', 'delete_other_workplace']);

        Role::create(['name' => 'READ-DATA-USERS'])->givePermissionTo('read_user');
        Role::create(['name' => 'READ-WRITE-DATA-USERS'])->givePermissionTo(['read_user', 'edit_user', 'create_user', 'edit_other_user']);
        Role::create(['name' => 'ADMIN-DATA-USERS'])->givePermissionTo(['read_user', 'edit_user', 'create_user', 'edit_other_user', 'delete_user', 'delete_other_user']);
    }
}
