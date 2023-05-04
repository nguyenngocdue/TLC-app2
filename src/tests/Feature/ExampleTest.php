<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Role_set;
use App\Models\User;
use App\Utils\Support\Entities;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    private function getUser()
    {
        return [
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'full_name' => 'Administrator',
            'first_name' => 'Fortune',
            'last_name' => 'Truong',
            'settings' => "[]",
        ];
    }

    public function test_the_view_all_screen_access_with_unauthenticated_user()
    {
        // $this->withoutExceptionHandling();

        $entities = Entities::getAll();
        foreach ($entities as $entity) {
            // $entity = $entities[0];
            $table = $entity->getTable();
            $route = route($table . '.index');
            // dump($route);
            $response = $this
                // ->actingAs($user)
                ->get($route);

            $response->assertStatus(302);
            $response->assertRedirectToRoute('login');
        }
    }

    private function load_permissions_to_table()
    {
        $entities = Entities::getAll();
        $permissions = [];
        foreach ($entities as $index => $entity) {
            $table = $entity->getTable();
            foreach (['read', 'create', 'edit', 'edit-others', 'delete', 'delete-others'] as $prefix) {
                $key = "$prefix-$table";
                $permissions[] = "('$key','web')";
            }
        }
        $sql = "INSERT INTO permissions(name, guard_name) VALUES";
        $sql .= join(",", $permissions);
        DB::insert($sql);
        $this->assertCount(sizeof($permissions), Permission::all());
        dump("Created " . sizeof($permissions) . " permission lines.");
    }

    private function load_permissions_to_role()
    {
        $entities = Entities::getAll();
        $keysArray = [];
        $permissionIndex = 1;
        $roleIndex = 1;
        $roleArray = [];
        foreach ($entities as $entity) {
            $table = $entity->getTable();
            $key1 = 'ADMIN-DATA-' . Str::upper($table);
            $roleArray[] = $key1;
            foreach (['read', 'create', 'edit', 'edit-others', 'delete', 'delete-others'] as $prefix) {
                $keysArray[] = "(" . ($roleIndex) . "," . ($permissionIndex) . ")";
                $permissionIndex++;
            }
            $roleIndex++;
        }
        $roles = [];
        foreach ($roleArray as $key) $roles[] = "('$key', 'web')";
        $sql = "INSERT INTO roles(name, guard_name) VALUES";
        $sql .= join(",", $roles);
        DB::insert($sql);
        $this->assertCount(sizeof($roles), Role::all());
        dump("Created " . sizeof($roles) . " role lines.");
        // dump($keysArray);

        $sql = "INSERT INTO role_has_permissions(role_id, permission_id) VALUES ";
        $sql .= join(",", $keysArray);
        DB::insert($sql);
        dump("Created " . sizeof($keysArray) . " role-permission lines.");
        // $this->assertCount(sizeof($roles), Role::all());
    }

    private function load_roles_to_roleset()
    {
        $entities = Entities::getAll();
        $rolesArray = [];
        $roleIndex = 1;
        foreach ($entities as $entity) {
            $rolesArray[] = "($roleIndex, 1)";
            $roleIndex++;
        }
        $sql = "INSERT INTO role_set_has_roles(role_id, role_set_id) VALUES";
        $sql .= join(",", $rolesArray);
        // dump($sql);
        DB::query($sql);
        dump("Created " . 1 . " roleset lines.");
        dump("Created " . sizeof($rolesArray) . " roleset-role lines.");
        // $roleset->giveRoleTo($key1sArray);
        Role_set::create(['name' => 'admin', 'guard_name' => 'web']);
    }

    private function load_permission_to_admin()
    {
        $entities = Entities::getAll();
        $roleset = Role_set::create(['name' => 'admin', 'guard_name' => 'web']);
        $key1sArray = [];
        foreach ($entities as $index => $entity) {
            $keysArray = [];
            $table = $entity->getTable();
            $key1 = 'ADMIN-DATA-' . Str::upper($table);
            $role = Role::create(['name' => $key1, 'guard_name' => 'web']);
            $key1sArray[] = $key1;
            foreach (['read', 'create', 'edit', 'edit-others', 'delete', 'delete-others'] as $prefix) {
                $key = "$prefix-$table";
                $keysArray[] = $key;
                Permission::create(['name' => $key, 'guard_name' => 'web']);
            }

            $role->givePermissionTo($keysArray);
            dump("$index -> $table");
        }
        // $sql = "INSERT INTO permissions(name, guard_name) VALUES";
        // $sql .= join(",", $permissions);
        // // dump($sql);
        // DB::query($sql);
        $roleset->giveRoleTo($key1sArray);
    }

    public function test_the_view_all_screen_access_with_authenticated_user()
    {
        $this->withoutExceptionHandling();
        // $this->load_permissions_to_table();
        // $this->load_permissions_to_role();
        // $this->load_roles_to_roleset();
        // dd();
        $this->load_permission_to_admin();
        // app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $user = User::create($this->getUser());
        $user->assignRoleset('admin');

        $entities = Entities::getAll();
        foreach ($entities as $entity) {
            // $entity = $entities[0];
            $table = $entity->getTable();
            $route = route($table . '.index');
            // dump("ROUTE", $route);
            $response = $this
                ->actingAs($user)
                ->get($route);

            $response->assertStatus(200);
        }
    }
}
