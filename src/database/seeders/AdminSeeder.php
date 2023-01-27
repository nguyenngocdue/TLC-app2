<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
                "first_name" => 'Admin',
                "last_name" => 'Super',
                "full_name" => 'admin',
                "name" => 'admin',
                "email" => 'admin',
                "email_verified_at" => now(),
                "settings" => [],
                "position_rendered" => "The Super Administrator",
                "password" => Hash::make('admin')
            ]
        )->assignRoleSet('admin');
    }
}
