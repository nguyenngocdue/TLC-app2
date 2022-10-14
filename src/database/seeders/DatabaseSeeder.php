<?php

namespace Database\Seeders;

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
            Time_Keep_TypeSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            RoleSetSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            PostSeeder::class,
            MediaSeeder::class,
            // WorkplaceSeeder::class,
        ]);
    }
}
