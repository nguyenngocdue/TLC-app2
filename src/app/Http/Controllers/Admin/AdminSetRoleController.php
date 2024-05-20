<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Role_set;
use App\Utils\Support\Entities;
use Illuminate\Http\Request;

class AdminSetRoleController extends Controller
{
    public function getType()
    {
        return "role";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleSetSelected = Role_set::first();
        $selected = $roleSetSelected->id;
        [$roles, $lastRoleNames, $roleSets, $roleUsing, $entities] = $this->getRoleSetRole($roleSetSelected);
        return view('admin.renderset.roles.index', [
            'roles' => $roles,
            'lastRoleNames' => $lastRoleNames,
            'selected' => $selected,
            'roleSetSelected' => $roleSetSelected,
            'roleSets' => $roleSets,
            'roleUsing' => $roleUsing,
            'entities' => $entities
        ]);
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
        $selected = $request->input('roleSet_id');
        $roleSetSelected = Role_set::findById($selected);
        [$roles, $lastRoleNames, $roleSets, $roleUsing, $entities] = $this->getRoleSetRole($roleSetSelected);
        return view('admin.renderset.roles.index', [
            'roles' => $roles,
            'lastRoleNames' => $lastRoleNames,
            'selected' => $selected,
            'roleSetSelected' => $roleSetSelected,
            'roleSets' => $roleSets,
            'roleUsing' => $roleUsing,
            'entities' => $entities
        ]);
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
        $roleSetRequest = $request->input('roleSet_id');
        $checkedSetRequest = $request->input('checked');
        $roleSelected = Role_set::findById($roleSetRequest);
        $roleSelected->syncRoles($checkedSetRequest);
        // toastr()->success('Sync Roles successfully!', 'Sync Roles');
        return $this->redirectBack($roleSetRequest);
    }

    private function redirectBack($selected)
    {
        $roleSetSelected = Role_set::findById($selected);
        [$roles, $lastRoleNames, $roleSets, $roleUsing, $entities] = $this->getRoleSetRole($roleSetSelected);
        return view('admin.renderset.roles.index', [
            'roles' => $roles,
            'lastRoleNames' => $lastRoleNames,
            'selected' => $selected,
            'roleSetSelected' => $roleSetSelected,
            'roleSets' => $roleSets,
            'roleUsing' => $roleUsing,
            'entities' => $entities
        ]);
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
    private function getRoleSetRole($roleSetSelected)
    {
        $roles = Role::orderBy('name')->get();
        $roleSets = Role_set::orderBy('name')->get();
        $roleUsing = $roleSetSelected->roles;
        $lastRoleNames = [];
        foreach ($roles as $role) {
            $arrayRoleName = explode('-', $role->name);
            $lastRoleName = end($arrayRoleName);
            array_push($lastRoleNames, $lastRoleName);
        }
        $entities = array_map(function ($entity) {
            return strtoupper($entity->getTable());
        }, Entities::getAll());
        $lastRoleNames = array_unique($lastRoleNames);
        return [$roles, $lastRoleNames, $roleSets, $roleUsing, $entities];
    }
}
