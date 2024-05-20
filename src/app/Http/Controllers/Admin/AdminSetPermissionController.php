<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Utils\Support\Entities;
use Illuminate\Http\Request;

class AdminSetPermissionController extends Controller
{
    public function getType()
    {
        return "permission";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleSelected = Role::first();
        $selected = $roleSelected->id;
        [$roles, $entities, $removeLastPermissionNames, $permissions, $permissionsRoles] = $this->getRolePermissions($roleSelected);
        return view('admin.renderset.permissions.index')->with(compact('roles', 'removeLastPermissionNames', 'selected', 'roleSelected', 'entities', 'permissions', 'permissionsRoles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $selected = $request->input('role_id');
        $roleSelected = Role::findById($selected);
        [$roles, $entities, $removeLastPermissionNames, $permissions, $permissionsRoles] = $this->getRolePermissions($roleSelected);
        return view('admin.renderset.permissions.index')->with(compact('roles', 'removeLastPermissionNames', 'selected', 'roleSelected', 'entities', 'permissions', 'permissionsRoles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }
    /**
     * Store a newly created resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store2(Request $request)
    {
        $roleRequest = $request->input('role_id');
        $modelRequest = $request->input('model');
        $checkedRequest = $request->input('checked');
        $roleSelected = Role::findById((int)$roleRequest, 'web');
        $roleSelected->syncPermissions($checkedRequest);
        // toastr()->success('Sync Permissions successfully!', 'Sync Permissions');
        return $this->redirectBack($roleRequest);
    }

    private function redirectBack($selected)
    {
        $roleSelected = Role::findById((int)$selected, 'web');
        [$roles, $entities, $removeLastPermissionNames, $permissions, $permissionsRoles] = $this->getRolePermissions($roleSelected);
        return view('admin.renderset.permissions.index')->with(compact('roles', 'removeLastPermissionNames', 'selected', 'roleSelected', 'entities', 'permissions', 'permissionsRoles'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)

    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
    private function getRolePermissions($roleSelected)
    {
        $roles = Role::orderBy('name')->get();
        $entities = Entities::getAll();
        $permissions = Permission::orderBy('name')->get();
        $permissionsRoles = $roleSelected->permissions;
        $removeLastPermissionNames = [];
        foreach ($permissions as $permission) {
            $arrayPermissionName = explode('-', $permission->name);
            array_pop($arrayPermissionName);
            $var = implode(' ', $arrayPermissionName);
            array_push($removeLastPermissionNames, $var);
        }
        $removeLastPermissionNames = array_unique($removeLastPermissionNames);
        return [$roles, $entities, $removeLastPermissionNames, $permissions, $permissionsRoles];
    }
}
