<?php

namespace Database\Seeders;

use App\Models\RoleSet;
use App\Utils\Support\Entities;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $entities = Entities::getAll();
            $names = [];
            foreach ($entities as $entity) {
                $name = $entity->getTable();
                $names[] = Str::upper($name);
            }
            $roles = array_map(fn ($item) => "ADMIN-DATA-$item", $names);
            RoleSet::create(['name' => 'admin'])->giveRoleTo([$roles]);
            // RoleSet::create(['name' => 'admin'])->giveRoleTo(['ADMIN-DATA-MEDIA', 'ADMIN-DATA-POSTS', 'ADMIN-DATA-WORKPLACES', 'ADMIN-DATA-USERS']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
