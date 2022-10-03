<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PostSeeder::class,
            MediaSeeder::class,
            WorkplaceSeeder::class,
        ]);
        // $this->call([
        //     PermissionSeeder::class,
        //     RoleSeeder::class,
        //     RoleSetSeeder::class,
        //     AdminSeeder::class,
        // ]);
    }
}
