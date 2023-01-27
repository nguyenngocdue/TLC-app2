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
            PermissionSeeder::class,
            RoleSeeder::class,
            FieldSeeder::class,
            ControlTypeSeeder::class,

            // RoleSetSeeder::class,
            // Time_Keep_TypeSeeder::class,
            // AdminSeeder::class,
            // PostSeeder::class,

            //To be removed:
            // AttachmentCategorySeeder::class,
        ]);
    }
}
