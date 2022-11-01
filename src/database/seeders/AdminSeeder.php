<?php

namespace Database\Seeders;

use App\Models\RoleSet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                "first_name" => '',
                "last_name" => '',
                "full_name" => 'admin',
                "name" => 'admin',
                "email" => 'admin',
                "email_verified_at" => now(),
                "settings" => [],
                "password" => Hash::make('admin')
            ]
        )->assignRoleSet('admin');
    }
}
