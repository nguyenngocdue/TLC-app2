<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleSet;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminSetRoleSetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        $pageLimit = request('page_limit');
        if ($pageLimit === null) $pageLimit = 20;
        $users = User::search($search)->query(function ($q) {
            $q->orderBy('id', 'asc');
        })->paginate($pageLimit);
        $roles = Role::all();
        $roleSetFirst = RoleSet::first();
        $roleUsing = $roleSetFirst->roles;
        return view('admin.renderset.rolesets.index')->with(compact('users', 'pageLimit', 'search', 'roles', 'roleSetFirst', 'roleUsing'));
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
        $user = User::find($id);
        $roleSets = RoleSet::all();
        $roleSetUsing = $user->roleSets;
        return view('admin.renderset.rolesets.edit', compact('user', 'roleSets', 'roleSetUsing', 'id'));
    }
    /**
     * Store a newly created resource in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store2(Request $request)
    {
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
        $roleSetUpdate = $request->input('roleSet');
        if ($roleSetUpdate == 'none') {
            Toastr::warning('Update Role Set failed ! Please choose another Role Set (none)', 'Set Role Set User');
            return back();
        }
        $user = User::find($id);
        $user->syncRoleSets($roleSetUpdate);
        Toastr::success('Sync Role Set successfully', 'Sync Role Set');
        return redirect()->back();
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
    public function impersonate($id)
    {
        $user = User::find($id);
        if ($user) {
            Session::put('impersonate', $user->id);
        }
        return redirect('/admin/setrolesets');
    }
}
