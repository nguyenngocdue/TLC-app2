<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class AdminSetPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleSelected = Role::first();
        $selected = $roleSelected->name;
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
        $selected = $request->input('role');
        $roleSelected = Role::findByName($selected);
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
        $roleRequest = $request->input('role');
        $modelRequest = $request->input('model');
        $checkedRequest = $request->input('checked');
        $roleSelected = Role::findByName($roleRequest);
        $roleSelected->syncPermissions($checkedRequest);
        return $this->redirectBack($roleRequest);
    }

    private function redirectBack($selected)
    {
        $roleSelected = Role::findByName($selected);
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
    private function getAvailableModels($model)
    {

        $models = [];
        $modelsPath = app_path($model);
        $modelFiles = File::allFiles($modelsPath);
        foreach ($modelFiles as $modelFile) {
            $models[] = 'App\\Models\\' . $modelFile->getFilenameWithoutExtension();
        }

        return $models;
    }
    private function checkHasPermissionModel($entities)
    {
        $array = [];
        foreach ($entities as $entity) {
            $value = in_array('App\Utils\PermissionTraits\CheckPermissionEntities', class_uses_recursive($entity));
            if ($value) {
                $model = App::make($entity);
                array_push($array, $model);
            }
        }
        return $array;
    }
    private function getRolePermissions($roleSelected)
    {
        $roles = Role::all();
        $models = $this->getAvailableModels("Models");
        $entities = $this->checkHasPermissionModel($models);
        $permissions = Permission::all();
        $permissionsRoles = $roleSelected->permissions;
        $removeLastPermissionNames = [];
        foreach ($permissions as $permission) {
            $arrayPermissionName = explode('_', $permission->name);
            array_pop($arrayPermissionName);
            $var = implode(' ', $arrayPermissionName);
            array_push($removeLastPermissionNames, $var);
        }
        $removeLastPermissionNames = array_unique($removeLastPermissionNames);
        return [$roles, $entities, $removeLastPermissionNames, $permissions, $permissionsRoles];
    }
}
