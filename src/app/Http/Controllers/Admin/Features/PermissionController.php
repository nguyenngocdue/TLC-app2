<?php

namespace App\Http\Controllers\Admin\Features;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Permission;

class PermissionController extends AdminController
{
    protected $type = 'permissions';
    protected $model = Permission::class;
    protected $validateCreate = ['name' => 'required|min:3|max:255|unique:permissions'];
    protected $validateUpdate = [
        'name' => 'required|min:3|max:255',
        'guard_name' => 'required|min:3|max:255'
    ];
}
