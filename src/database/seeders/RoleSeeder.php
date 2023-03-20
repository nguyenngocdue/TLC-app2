<?php

namespace Database\Seeders;

use App\Utils\Support\Entities;
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
        try {
            $entities = Entities::getAll();
            foreach ($entities as $entity) {
                $name = $entity->getTable();
                $nameUpper = Str::upper($name);
                Role::updateOrCreate(["name" => "READ-DATA-$nameUpper"])->givePermissionTo("read-$name");
                Role::updateOrCreate(["name" => "READ-WRITE-DATA-$nameUpper"])->givePermissionTo(["read-$name", "edit-$name", "create-$name", "edit-others-$name"]);
                Role::updateOrCreate(["name" => "ADMIN-DATA-$nameUpper"])->givePermissionTo(["read-$name", "edit-$name", "create-$name", "edit-others-$name", "delete-$name", "delete-others-$name"]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
