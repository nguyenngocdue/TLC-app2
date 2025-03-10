<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreatePermissionAndRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ndc:permission {name : The name of the entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all permissions and roles for a new entity type';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $name = Str::plural(Str::snake(trim($this->input->getArgument('name'))));
            $result = "read-$name|create-$name|edit-$name|edit-others-$name|delete-$name|delete-others-$name";
            $permissions = explode('|', $result);
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }
            $nameRole = Str::upper($name);
            Role::create(['name' => "READ-DATA-$nameRole"])->givePermissionTo("read-$name");
            Role::create(['name' => "READ-WRITE-DATA-$nameRole"])->givePermissionTo(["read-$name", "edit-$name", "create-$name", "edit-others-$name"]);
            Role::create(['name' => "ADMIN-DATA-$nameRole"])->givePermissionTo(["read-$name", "edit-$name", "create-$name", "edit-others-$name", "delete-$name", "delete-others-$name"]);
            $this->info("Create permission and role {$name} successfully");
        } catch (\Throwable $th) {
            $this->error($th);
        }
    }
}
