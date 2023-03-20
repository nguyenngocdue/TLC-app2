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
                Permission::updateOrCreate(['name' => "read-$name"]);
                Permission::updateOrCreate(['name' => "create-$name"]);
                Permission::updateOrCreate(['name' => "edit-$name"]);
                Permission::updateOrCreate(['name' => "edit-others-$name"]);
                Permission::updateOrCreate(['name' => "delete-$name"]);
                Permission::updateOrCreate(['name' => "delete-others-$name"]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
