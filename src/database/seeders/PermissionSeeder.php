<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Utils\Support\Entities;
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
        try {
            $entities = Entities::getAll();
            foreach ($entities as $entity) {
                $name = $entity->getTable();
                Permission::create(['name' => "read-$name"]);
                Permission::create(['name' => "create-$name"]);
                Permission::create(['name' => "edit-$name"]);
                Permission::create(['name' => "edit-others-$name"]);
                Permission::create(['name' => "delete-$name"]);
                Permission::create(['name' => "delete-others-$name"]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
