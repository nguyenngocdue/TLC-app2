<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleSet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class AdminSetRoleController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roleSetSelected = RoleSet::first();
        $selected = $roleSetSelected->name;
        [$roles, $lastRoleNames, $roleSets, $roleUsing] = $this->getRoleSetRole($roleSetSelected);
        return view('admin.renderset.roles.index')->with(compact('roles', 'selected', 'lastRoleNames', 'roleSetSelected', 'roleSets', 'roleUsing'));
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
        $selected = $request->input('roleSet');
        $roleSetSelected = RoleSet::findByName($selected);
        [$roles, $lastRoleNames, $roleSets, $roleUsing] = $this->getRoleSetRole($roleSetSelected);

        return view('admin.renderset.roles.index')->with(compact('roles', 'lastRoleNames', 'selected', 'roleSetSelected', 'roleSets', 'roleUsing'));
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
        $roleSetRequest = $request->input('roleSet');
        $checkedSetRequest = $request->input('checked');
        $roleSelected = RoleSet::findByName($roleSetRequest);
        $roleSelected->syncRoles($checkedSetRequest);
        // Toastr::success('Sync Roles successfully!', 'Sync Roles');
        return $this->redirectBack($roleSetRequest);
    }

    private function redirectBack($selected)
    {
        $roleSetSelected = RoleSet::findByName($selected);
        [$roles, $lastRoleNames, $roleSets, $roleUsing] = $this->getRoleSetRole($roleSetSelected);
        return view('admin.renderset.roles.index')->with(compact('roles', 'lastRoleNames', 'selected', 'roleSetSelected', 'roleSets', 'roleUsing'));
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
        $roles = Role::all();
        $roleSets = RoleSet::all();
        $roleUsing = $roleSetSelected->roles;
        $lastRoleNames = [];
        foreach ($roles as $role) {
            $arrayRoleName = explode('-', $role->name);
            $lastRoleName = end($arrayRoleName);
            array_push($lastRoleNames, $lastRoleName);
        }
        $lastRoleNames = array_unique($lastRoleNames);
        return [$roles, $lastRoleNames, $roleSets, $roleUsing];
    }
}
